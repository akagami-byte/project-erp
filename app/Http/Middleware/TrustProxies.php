<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     * 
     * CRITICAL: IP Cloudflare tunnel clients.
     * Jangan gunakan '*' di production (insecure).
     * Gunakan IP range spesifik Cloudflare.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = [
        '127.0.0.1',           // Loopback (untuk testing lokal)
        '::1',                 // IPv6 loopback
        '173.245.48.0/20',     // Cloudflare IP Range 1
        '103.21.244.0/22',     // Cloudflare IP Range 2
        '103.22.200.0/22',     // Cloudflare IP Range 3
        '103.31.4.0/22',       // Cloudflare IP Range 4
        '141.101.64.0/18',     // Cloudflare IP Range 5
        '108.162.192.0/18',    // Cloudflare IP Range 6
        '190.93.240.0/20',     // Cloudflare IP Range 7
        '188.114.96.0/20',     // Cloudflare IP Range 8
        '197.234.240.0/22',    // Cloudflare IP Range 9
        '198.41.128.0/17',     // Cloudflare IP Range 10
        '162.158.0.0/15',      // Cloudflare IP Range 11
        '172.64.0.0/13',       // Cloudflare IP Range 12
        '131.0.72.0/22',       // Cloudflare IP Range 13
    ];

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
