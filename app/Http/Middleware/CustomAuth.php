<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Society;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->query('token');

        if (!$token) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        $society = Society::where('login_tokens', $token)->first();

        if (!$society) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        Auth::loginUsingId($society->id);

        return $next($request);
    }
}
