<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandleLivewireFileUploads
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->hasHeader('X-Livewire')) {
            return $next($request)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
                ->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Origin, Authorization');
        }

        return $next($request);
    }
} 