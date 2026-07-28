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
            return redirect()->route('voter.portal.login')->with('error', 'Please log in to access the voter portal.');
        }

        return $next($request);
    }
}
