<?php

namespace App\Http\Controllers;

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

    public function logout()
    {
        session()->forget([
            'admin_logged_in',
            'admin_email',
            'admin_user_id',
            'admin_user_name',
            'admin_role',
        ]);

        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('home');
    }
}
