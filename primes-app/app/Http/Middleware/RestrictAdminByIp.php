<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictAdminByIp
{
    // List of allowed IPs
    protected $allowedIps = [
        //'127.0.0.1',
        //'::1',
        // Add more as needed
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if (!in_array($request->ip(), $this->allowedIps)) {
            abort(403, 'Access denied');
        }
        return $next($request);
    }
}
