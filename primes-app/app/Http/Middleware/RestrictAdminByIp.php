<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictAdminByIp
{
    // List of allowed IPs
    protected $allowedIps = [
        '10.0.0.9',
        // Add more as needed
    ];

    public function handle(Request $request, Closure $next): Response
    {
        die('Your IP: ' . $request->ip());
    }
}