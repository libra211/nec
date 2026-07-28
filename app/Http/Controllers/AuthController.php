<?php

namespace App\Http\Controllers;

use App\Models\OtpCode;
use App\Models\User;
use App\Support\SecurityAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('GET')) {
            return view('admin.login');
        }

        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (SecurityAudit::checkBruteForce($validated['email'])) {
            SecurityAudit::auditLogin($validated['email'], false);

            return back()->withInput()->with('error', 'Too many failed attempts. Please try again in 15 minutes.');
        }

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            SecurityAudit::auditLogin($validated['email'], false);

            return back()->withInput()->with('error', 'Invalid email or password.');
        }

        SecurityAudit::auditLogin($validated['email'], true);

        session([
            'admin_logged_in' => true,
            'admin_email' => $user->email,
            'admin_user_id' => $user->id,
            'admin_user_name' => $user->name,
            'admin_role' => $user->role ?? 'admin',
            'admin_state' => $user->state ?? '',
            'admin_constituency' => $user->constituency ?? '',
        ]);

        $user->update(['last_login' => now()]);

        return redirect()->route('admin.dashboard');
    }

    public function adminLogin(Request $request)
    {
        // Step 1: Show email form (GET)
        if ($request->isMethod('GET')) {
            session()->forget(['otp_email', 'otp_verified']);
            return view('admin.login', ['step' => 'email']);
        }

        // Determine which step we're on
        $step = $request->input('step', 'email');

        // Step 1: Receive email, validate user exists, send OTP
        if ($step === 'email') {
            $validated = $request->validate([
                'email' => 'required|email',
            ]);

            $email = $validated['email'];
            $user = User::where('email', $email)->first();

            if (!$user) {
                return back()->withInput()->with('error', 'No account found with that email address.');
            }

            if ($user->status !== 'active') {
                return back()->withInput()->with('error', 'Your account has been deactivated. Please contact support.');
            }

            // Generate and "send" OTP
            $otp = OtpCode::generate($email, 'login', 10);

            // Store email in session for step 2
            session(['otp_email' => $email]);

            // In production, send via email/SMS here:
            // Mail::to($email)->send(new OtpMail($otp->code));
            // For demo, we log it
            \Log::info("OTP for {$email}: {$otp->code}");

            return view('admin.login', [
                'step' => 'otp',
                'email' => $email,
                'otp_sent' => true,
            ]);
        }

        // Step 2: Receive OTP, validate, and log in
        if ($step === 'otp') {
            $validated = $request->validate([
                'email' => 'required|email',
                'otp' => 'required|string|size:6',
            ]);

            $email = $validated['email'];
            $otpCode = $validated['otp'];

            // Verify OTP
            if (!OtpCode::verify($email, $otpCode, 'login')) {
                SecurityAudit::auditLogin($email, false);

                return view('admin.login', [
                    'step' => 'otp',
                    'email' => $email,
                    'error' => 'Invalid or expired OTP code. Please try again.',
                ]);
            }

            // Find user and log in
            $user = User::where('email', $email)->first();

            if (!$user || $user->status !== 'active') {
                return redirect()->route('admin.login')->with('error', 'Account not found or deactivated.');
            }

            SecurityAudit::auditLogin($email, true);

            session([
                'admin_logged_in' => true,
                'admin_email' => $user->email,
                'admin_user_id' => $user->id,
                'admin_user_name' => $user->name,
                'admin_role' => $user->role ?? 'admin',
                'admin_state' => $user->state ?? '',
                'admin_constituency' => $user->constituency ?? '',
            ]);

            $user->update(['last_login' => now()]);

            session()->forget(['otp_email', 'otp_verified']);

            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('admin.login');
    }

    public function logout()
    {
        session()->forget([
            'admin_logged_in',
            'admin_email',
            'admin_user_id',
            'admin_user_name',
            'admin_role',
            'otp_email',
            'otp_verified',
        ]);

        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('home');
    }
}
