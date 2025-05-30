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
<<<<<<< HEAD
        echo $request->ip();
        exit;
=======
        if (!in_array($request->ip(), $this->allowedIps)) {
            abort(403, 'Access denied');
        }
        return $next($request);
>>>>>>> 4a7e414d6946af68af74cc56b7bc71be4ee52444
    }
}