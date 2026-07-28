<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VoterMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('voter_logged_in', false)) {
            $token = $request->cookie('nec_remember');
            $type = $request->cookie('nec_remember_type');
            if ($token && $type === 'voter') {
                $voterAccount = \App\Models\VoterAccount::where('remember_token', $token)->first();
                if ($voterAccount) {
                    $voter = \App\Models\Voter::where('voter_id', $voterAccount->voter_id)->first();
                    if ($voter) {
                        session([
                            'voter_logged_in' => true,
                            'voter_id' => $voter->voter_id,
                            'voter_user_id' => $voterAccount->id,
                            'voter_name' => $voter->full_name ?? 'Voter',
                        ]);
                        return $next($request);
                    }
                }
            }
            return redirect()->route('login')->with('error', 'Please log in to access the voter portal.');
        }

        return $next($request);
    }
}
