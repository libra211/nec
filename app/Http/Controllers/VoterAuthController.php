<?php

namespace App\Http\Controllers;

use App\Models\Constituency;
use App\Models\OtpCode;
use App\Models\Voter;
use App\Models\VoterAccount;
use App\Models\VoterTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VoterAuthController extends Controller
{
    public function showLogin()
    {
        return view('voter.portal.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('login');
        $password = $request->input('password');

        $account = VoterAccount::where('email', $login)
            ->orWhere('voter_id', $login)
            ->first();

        if (!$account) {
            return back()->withInput()->with('error', 'Invalid credentials. Please try again.');
        }

        if ($account->locked_until && $account->locked_until->isFuture()) {
            $minutes = ceil($account->locked_until->diffInSeconds(now()) / 60);
            return back()->withInput()->with('error', "Account locked. Try again in {$minutes} minutes.");
        }

        $valid = false;

        if ($account->email === $login) {
            $valid = Hash::check($password, $account->password);
        } else {
            $valid = Hash::check($password, $account->password) || ($account->pin_code && Hash::check($password, $account->pin_code));
        }

        if (!$valid) {
            $attempts = $account->login_attempts + 1;
            $updates = ['login_attempts' => $attempts];

            if ($attempts >= 5) {
                $updates['locked_until'] = now()->addMinutes(30);
                $updates['login_attempts'] = 0;
            }

            $account->update($updates);

            if ($attempts >= 5) {
                return back()->withInput()->with('error', 'Too many failed attempts. Account locked for 30 minutes.');
            }

            $remaining = 5 - $attempts;
            return back()->withInput()->with('error', "Invalid credentials. {$remaining} attempts remaining.");
        }

        $voter = Voter::where('voter_id', $account->voter_id)->first();

        $account->update([
            'login_attempts' => 0,
            'locked_until' => null,
            'last_login' => now(),
        ]);

        $request->session()->regenerate();

        session([
            'voter_logged_in' => true,
            'voter_id' => $voter->voter_id,
            'voter_user_id' => $account->id,
            'voter_name' => $voter->full_name,
        ]);

        return redirect()->route('voter.portal.dashboard');
    }

    public function showRegister()
    {
        return view('voter.portal.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'voter_id' => 'required_without_all:national_id,full_name,dob',
            'national_id' => 'required_without:voter_id|string|max:50',
            'full_name' => 'required_without:voter_id|string|max:255',
            'dob' => 'required_without:voter_id|date|before:today',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $voter = null;

        if ($request->filled('voter_id')) {
            $voter = Voter::where('voter_id', $request->input('voter_id'))->first();
        } elseif ($request->filled('national_id') && $request->filled('full_name') && $request->filled('dob')) {
            $voter = Voter::where('national_id', $request->input('national_id'))
                ->where('full_name', $request->input('full_name'))
                ->where('dob', $request->input('dob'))
                ->first();
        }

        if (!$voter) {
            return back()->withInput()->with('error', 'Voter not found. Please check your details and try again.');
        }

        $existing = VoterAccount::where('voter_id', $voter->voter_id)->first();
        if ($existing) {
            return back()->withInput()->with('error', 'An account already exists for this voter.');
        }

        $emailTaken = VoterAccount::where('email', $request->input('email'))->where('voter_id', '!=', $voter->voter_id)->first();
        if ($emailTaken) {
            return back()->withInput()->with('error', 'This email is already registered to another account.');
        }

        $email = $request->input('email');
        $otp = OtpCode::generate($email, 'voter_account', 10);
        Mail::to($email)->send(new \App\Mail\OtpNotification($otp->code, 'voter_account'));

        session([
            'voter_account_pending' => [
                'voter_id' => $voter->voter_id,
                'email' => $email,
                'password' => Hash::make($request->input('password')),
            ],
            'voter_account_otp_identifier' => $email,
        ]);

        return response()
            ->view('voter.portal.verify-otp', [
                'identifier' => $email,
                'otpSent' => true,
            ])
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function verifyEmailOtp(Request $request)
    {
        $pending = session('voter_account_pending');

        if ($request->isMethod('GET')) {
            if (!$pending) {
                return redirect()->route('voter.portal.register')->with('error', 'Please start account creation again.');
            }
            return response()
                ->view('voter.portal.verify-otp', ['identifier' => session('voter_account_otp_identifier')])
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        }

        if (!$pending) {
            return redirect()->route('voter.portal.register')->with('error', 'Session expired. Please start again.');
        }

        $identifier = session('voter_account_otp_identifier');
        $request->validate(['otp' => 'required|string|size:6']);

        if (!OtpCode::verify($identifier, $request->input('otp'), 'voter_account')) {
            return response()
                ->view('voter.portal.verify-otp', [
                    'identifier' => $identifier,
                    'error' => 'Invalid or expired code. Please try again.',
                ])
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        }

        $voter = Voter::where('voter_id', $pending['voter_id'])->first();

        $account = VoterAccount::create([
            'voter_id' => $pending['voter_id'],
            'email' => $pending['email'],
            'password' => $pending['password'],
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        session()->forget(['voter_account_pending', 'voter_account_otp_identifier']);
        session()->regenerate();

        session([
            'voter_logged_in' => true,
            'voter_id' => $pending['voter_id'],
            'voter_user_id' => $account->id,
            'voter_name' => $voter?->full_name ?? 'Voter',
        ]);

        return redirect()->route('voter.portal.dashboard')->with('success', 'Account verified and activated successfully.');
    }

    public function resendEmailOtp(Request $request)
    {
        $identifier = session('voter_account_otp_identifier');
        if (!$identifier) {
            return redirect()->route('voter.portal.register')->with('error', 'Session expired. Please start again.');
        }

        $otp = OtpCode::generate($identifier, 'voter_account', 10);
        Mail::to($identifier)->send(new \App\Mail\OtpNotification($otp->code, 'voter_account'));

        return response()
            ->view('voter.portal.verify-otp', [
                'identifier' => $identifier,
                'otpSent' => true,
                'success' => 'A new code has been sent to your email.',
            ])
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function logout()
    {
        session()->forget(['voter_logged_in', 'voter_id', 'voter_user_id', 'voter_name']);
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('voter.portal.login')->with('success', 'You have been logged out.');
    }

    public function forgotPassword()
    {
        $step = 1;
        $resetSuccess = false;
        return view('voter.portal.forgot-password', compact('step', 'resetSuccess'));
    }

    public function forgotPasswordSubmit(Request $request)
    {
        $request->validate([
            'voter_id' => 'required|string',
            'dob' => 'required|date',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $voter = Voter::where('voter_id', $request->input('voter_id'))
            ->where('dob', $request->input('dob'))
            ->first();

        if (!$voter) {
            return back()->withInput()->with('error', 'Voter not found. Please check your Voter ID and date of birth.');
        }

        $account = VoterAccount::where('voter_id', $voter->voter_id)->first();

        if (!$account) {
            return back()->withInput()->with('error', 'No account found for this voter. Please register first.');
        }

        $account->update([
            'password' => Hash::make($request->input('password')),
            'login_attempts' => 0,
            'locked_until' => null,
        ]);

        return redirect()->route('voter.portal.login')->with('success', 'Password reset successfully. Please log in.');
    }

    public function dashboard()
    {
        $voter = Voter::where('voter_id', session('voter_id'))->firstOrFail();

        $transfers = VoterTransfer::where('voter_id', $voter->voter_id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('voter.portal.dashboard', compact('voter', 'transfers'));
    }

    public function profile()
    {
        $voter = Voter::where('voter_id', session('voter_id'))->firstOrFail();

        return view('voter.portal.profile', compact('voter'));
    }

    public function updateProfile(Request $request)
    {
        $voter = Voter::where('voter_id', session('voter_id'))->firstOrFail();

        $request->validate([
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $voter->update([
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function idCard()
    {
        $voter = Voter::where('voter_id', session('voter_id'))->firstOrFail();

        return view('voter.portal.id-card', compact('voter'));
    }

    public function transfer()
    {
        $voter = Voter::where('voter_id', session('voter_id'))->firstOrFail();
        $constituencies = Constituency::where('status', 'active')->orderBy('name')->get();
        $states = DB::table('nec_states')->where('status', 'active')->orderBy('name')->pluck('name');

        return view('voter.portal.transfer', compact('voter', 'constituencies', 'states'));
    }

    public function transferSubmit(Request $request)
    {
        $voter = Voter::where('voter_id', session('voter_id'))->firstOrFail();

        $request->validate([
            'to_constituency' => 'required|exists:nec_constituencies,name',
            'reason' => 'required|string|max:1000',
        ]);

        $pending = VoterTransfer::where('voter_id', $voter->voter_id)
            ->whereIn('status', ['pending', 'submitted'])
            ->exists();

        if ($pending) {
            return back()->withInput()->with('error', 'You already have a pending transfer request.');
        }

        $toConstituency = Constituency::where('name', $request->input('to_constituency'))->first();

        VoterTransfer::create([
            'voter_id' => $voter->voter_id,
            'from_constituency' => $voter->constituency,
            'to_constituency' => $toConstituency->name,
            'from_state' => $voter->state,
            'to_state' => $toConstituency->state ?? $voter->state,
            'reason' => $request->input('reason'),
            'status' => 'pending',
        ]);

        \App\Models\Notification::notifyAdmins(
            "Transfer request: {$voter->full_name} ({$voter->voter_id}) from {$voter->constituency} to {$toConstituency->name}",
            [
                'title' => 'Transfer Request',
                'type' => 'transfer',
                'icon' => 'exchange-alt',
                'color' => 'info',
                'link' => route('admin.voter-transfers.index'),
            ]
        );

        return redirect()->route('voter.portal.transfer-status')->with('success', 'Transfer request submitted successfully.');
    }

    public function transferStatus()
    {
        $voter = Voter::where('voter_id', session('voter_id'))->firstOrFail();

        $transfers = VoterTransfer::where('voter_id', $voter->voter_id)
            ->orderByDesc('created_at')
            ->get();

        return view('voter.portal.transfer-status', compact('voter', 'transfers'));
    }

    public function verifyVoter(Request $request)
    {
        $voter = null;
        $verified = false;

        if ($request->filled('voter_id')) {
            $request->validate(['voter_id' => 'required|string']);
            $voter = Voter::where('voter_id', $request->input('voter_id'))->first();
            $verified = $voter !== null;
        }

        return view('voter.portal.verify', compact('voter', 'verified'));
    }
}
