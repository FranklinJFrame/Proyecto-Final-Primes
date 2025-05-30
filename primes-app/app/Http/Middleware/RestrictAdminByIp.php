<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictAdminByIp
{
    // List of allowed IPs
    protected $allowedIps = [
        '100.64.0.2',
        '100.64.0.4',

        // Add more as needed
    ];

    public function handle(Request $request, Closure $next): Response
    {
        echo $request->ip();
        exit;
    }
}