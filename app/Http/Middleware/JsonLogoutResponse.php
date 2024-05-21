<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JsonLogoutResponse
{
    public function handle(Request $request, Closure $next)
    {
        Auth::guard('web')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
