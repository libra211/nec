<?php

namespace App\Http\Controllers;

use App\Models\LoginLog;
use App\Models\OtpCode;
use App\Models\User;
use App\Models\Voter;
use App\Models\VoterAccount;
use App\Support\SecurityAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function unifiedLogin(Request $request)
    {
        // GET: show unified login form
        if ($request->isMethod('GET')) {
            session()->forget(['otp_identifier', 'otp_verified', 'pending_admin_id']);
            return response()
                ->view('auth.login')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        }

        $mode = $request->input('mode', 'email');
        $hasOtp = $request->filled('otp');

        // ==================== OTP VERIFICATION STEP (admin only) ====================
        if ($hasOtp) {
            $identifier = session('otp_identifier');
            $pendingId = session('pending_admin_id');

            if (!$identifier || !$pendingId) {
                return redirect()->route('login')->with('error', 'Session expired. Please login again.');
            }

            $validated = $request->validate(['otp' => 'required|string|size:6']);
            $otpCode = $validated['otp'];

            $pendingUser = User::find($pendingId);

            if (!OtpCode::verify($identifier, $otpCode, 'login')) {
                SecurityAudit::auditLogin($identifier, false);
                $this->logLoginAttempt($identifier, false, $pendingUser->name ?? $identifier, $pendingUser->role ?? 'admin');
                return response()
                    ->view('auth.login', [
                        'mode' => $mode,
                        'email' => $identifier,
                        'needsOtp' => true,
                        'otpSent' => true,
                        'loginIdentifier' => $identifier,
                        'error' => 'Invalid or expired OTP code. Please try again.',
                    ])
                    ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                    ->header('Pragma', 'no-cache')
                    ->header('Expires', '0');
            }

            $user = $pendingUser;

            if (!$user || $user->status !== 'active') {
                $this->logLoginAttempt($identifier, false, $user->name ?? $identifier, $user->role ?? 'admin');
                return redirect()->route('login')->with('error', 'Account not found or deactivated.');
            }

            SecurityAudit::auditLogin($identifier, true);
            $this->logLoginAttempt($identifier, true, $user->name, $user->role ?? 'admin');

            $request->session()->regenerate();

            session([
                'admin_logged_in' => true,
                'admin_email' => $user->email,
                'admin_user_id' => $user->id,
                'admin_user_name' => $user->name,
                'admin_role' => $user->role ?? 'admin',
                'admin_avatar' => $user->avatar,
                'admin_state' => $user->state ?? '',
                'admin_constituency' => $user->constituency ?? '',
            ]);

            $user->update(['last_login' => now()]);

            if (session('pending_remember')) {
                $this->setRememberMeCookie($user);
            }

            session()->forget(['otp_identifier', 'otp_verified', 'pending_admin_id', 'pending_remember']);

            return redirect()->route('admin.dashboard');
        }

        // ==================== RESEND OTP ====================
        if ($request->has('resend')) {
            $identifier = session('otp_identifier');
            if (!$identifier) {
                return redirect()->route('login')->with('error', 'Session expired. Please start over.');
            }
            $otp = OtpCode::generate($identifier, 'login', 10);
            \Log::info("OTP resent for {$identifier}: {$otp->code}");
            session()->flash('success', 'A new code has been sent to your email.');
            return response()
                ->view('auth.login', [
                    'mode' => $mode,
                    'email' => $identifier,
                    'needsOtp' => true,
                    'otpSent' => true,
                    'loginIdentifier' => $identifier,
                ])
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        }

        // ==================== INITIAL LOGIN STEP ====================
        $validated = $request->validate([
            'password' => 'required|string',
        ]);

        $password = $validated['password'];

        // Determine identifier
        if ($mode === 'email') {
            $identifier = $request->input('email');
            if (!$identifier) {
                return back()->withInput()->with('error', 'Please enter your email or Voter ID.');
            }
        } else {
            $identifier = $request->input('phone');
            if (!$identifier) {
                return back()->withInput()->with('error', 'Please enter your phone number.');
            }
        }

        // --- Try nec_users (admin/staff) ---
        $adminUser = null;
        if ($mode === 'email') {
            $adminUser = User::where('email', $identifier)->first();
        } else {
            $adminUser = User::where('phone', $identifier)->first();
        }

        if ($adminUser) {
            if (!Hash::check($password, $adminUser->password)) {
                SecurityAudit::auditLogin($identifier, false);
                $this->logLoginAttempt($identifier, false, $adminUser->name, $adminUser->role ?? 'admin');
                return back()->withInput()->with('error', 'Invalid credentials.');
            }

            if ($adminUser->status !== 'active') {
                $this->logLoginAttempt($identifier, false, $adminUser->name, $adminUser->role ?? 'admin');
                return back()->withInput()->with('error', 'Your account has been deactivated.');
            }

            // Admin/staff role → require OTP
            session([
                'otp_identifier' => $identifier,
                'pending_admin_id' => $adminUser->id,
                'pending_remember' => $request->has('remember'),
            ]);

            $otp = OtpCode::generate($identifier, 'login', 10);
            \Log::info("OTP for admin {$identifier}: {$otp->code}");

            return response()
                ->view('auth.login', [
                    'mode' => $mode,
                    'email' => $mode === 'email' ? $identifier : null,
                    'needsOtp' => true,
                    'otpSent' => true,
                    'loginIdentifier' => $identifier,
                ])
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        }

        // --- Try voter_accounts ---
        if ($mode === 'email') {
            $voterAccount = VoterAccount::where('email', $identifier)
                ->orWhere('voter_id', $identifier)
                ->first();
        } else {
            // Voters can't login by phone (no phone in voter_accounts)
            return back()->withInput()->with('error', 'No admin account found with that phone number.');
        }

        if ($voterAccount) {
            if (!Hash::check($password, $voterAccount->password)) {
                $attempts = $voterAccount->login_attempts + 1;
                $updates = ['login_attempts' => $attempts];
                if ($attempts >= 5) {
                    $updates['locked_until'] = now()->addMinutes(30);
                    $updates['login_attempts'] = 0;
                }
                $voterAccount->update($updates);
                $voterName = optional(Voter::where('voter_id', $voterAccount->voter_id)->first())->full_name ?? $identifier;
                $this->logLoginAttempt($identifier, false, $voterName, 'voter');
                if ($attempts >= 5) {
                    return back()->withInput()->with('error', 'Too many failed attempts. Account locked for 30 minutes.');
                }
                $remaining = 5 - $attempts;
                return back()->withInput()->with('error', "Invalid credentials. {$remaining} attempts remaining.");
            }

            $voter = Voter::where('voter_id', $voterAccount->voter_id)->first();

            $voterAccount->update([
                'login_attempts' => 0,
                'locked_until' => null,
                'last_login' => now(),
            ]);

            $request->session()->regenerate();

            session([
                'voter_logged_in' => true,
                'voter_id' => $voter->voter_id,
                'voter_user_id' => $voterAccount->id,
                'voter_name' => $voter->full_name ?? 'Voter',
            ]);

            if ($request->has('remember')) {
                $this->setRememberMeCookie($voterAccount, 'voter');
            }

            $this->logLoginAttempt($identifier, true, $voter->full_name ?? 'Voter', 'voter');

            return redirect()->route('voter.portal.dashboard');
        }

        $this->logLoginAttempt($identifier, false, '', 'unknown');

        return back()->withInput()->with('error', 'No account found with those credentials.');
    }

    public function login(Request $request)
    {
        return redirect()->route('login');
    }

    public function adminLogin(Request $request)
    {
        return redirect()->route('login');
    }

    private function logLoginAttempt(string $identifier, bool $success, ?string $name = null, ?string $role = null): void
    {
        try {
            $ip = request()->ip();
            $ua = request()->userAgent();
            $location = '';
            $isLocal = in_array($ip, ['127.0.0.1', '::1', 'localhost'], true);
            if ($isLocal) {
                $location = 'Local Machine';
            } elseif ($ip) {
                try {
                    $resp = Http::timeout(2)->get("http://ip-api.com/json/{$ip}?fields=city,regionName,country,lat,lon");
                    if ($resp->successful()) {
                        $data = $resp->json();
                        $parts = array_filter([$data['city'] ?? '', $data['regionName'] ?? '', $data['country'] ?? '']);
                        $location = implode(', ', $parts);
                    }
                } catch (\Throwable) {}
            }
            LoginLog::create([
                'identifier' => $identifier,
                'name' => $name,
                'ip_address' => $ip,
                'user_agent' => $ua,
                'location' => $location,
                'success' => $success,
                'role' => $role,
                'logged_at' => now(),
            ]);
        } catch (\Throwable $e) {
            \Log::warning('Failed to log login attempt: ' . $e->getMessage());
        }
    }

    private function setRememberMeCookie($user, string $type = 'admin'): void
    {
        $token = Str::random(60);
        $user->update(['remember_token' => $token]);

        cookie()->queue(cookie()->forever('nec_remember', $token));
        cookie()->queue(cookie()->forever('nec_remember_type', $type));
    }

    public function logout()
    {
        $token = request()->cookie('nec_remember');
        if ($token) {
            $user = User::where('remember_token', $token)->first();
            if ($user) {
                $user->update(['remember_token' => null]);
            } else {
                $voterAccount = VoterAccount::where('remember_token', $token)->first();
                if ($voterAccount) {
                    $voterAccount->update(['remember_token' => null]);
                }
            }
        }

        session()->forget([
            'admin_logged_in',
            'admin_email',
            'admin_user_id',
            'admin_user_name',
            'admin_role',
            'admin_state',
            'admin_constituency',
            'otp_email',
            'otp_verified',
            'otp_identifier',
            'pending_admin_id',
            'pending_remember',
            'voter_logged_in',
            'voter_id',
            'voter_user_id',
            'voter_name',
        ]);

        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login')
            ->withCookie(\Cookie::forget('nec_remember'))
            ->withCookie(\Cookie::forget('nec_remember_type'));
    }

    public function forgotPassword(Request $request)
    {
        if ($request->isMethod('GET')) {
            return response()
                ->view('auth.forgot-password')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        }

        $validated = $request->validate([
            'email' => 'required|email|exists:nec_users,email',
        ]);

        $email = $validated['email'];
        $user = User::where('email', $email)->first();

        if (!$user || $user->status !== 'active') {
            return back()->withInput()->with('error', 'Account not found or inactive.');
        }

        $otp = OtpCode::generate($email, 'password_reset', 10);
        \Log::info("Password reset OTP for {$email}: {$otp->code}");

        session(['reset_email' => $email]);

        return response()
            ->view('auth.forgot-password', [
                'email' => $email,
                'otpSent' => true,
            ])
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
