<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array|string|null
     */
    protected $proxies = '*'; // Trust all proxies (for most cloud hosts)

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;

    public function handle($request, \Closure $next)
    {
        // Log to confirm TrustProxies is running
        file_put_contents(storage_path('logs/trustproxies_test.log'), date('c') . "\n", FILE_APPEND);
        return parent::handle($request, $next);
    }
}
