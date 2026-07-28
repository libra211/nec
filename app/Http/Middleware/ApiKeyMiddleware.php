<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'API key required.'], 401);
        }

        $apiKey = ApiKey::where('key', $token)->where('status', 'active')->first();

        if (!$apiKey) {
            return response()->json(['error' => 'Invalid or inactive API key.'], 401);
        }

        if ($apiKey->expires_at && $apiKey->expires_at->isPast()) {
            return response()->json(['error' => 'API key has expired.'], 401);
        }

        $apiKey->update(['last_used_at' => now()]);

        $request->merge(['api_key' => $apiKey]);

        return $next($request);
    }
}
