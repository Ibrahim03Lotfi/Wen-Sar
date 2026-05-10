<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array<int, string>|string|null
     *
     * Configure via TRUSTED_PROXIES env variable (comma-separated IPs/ranges).
     * Examples:
     * - Single proxy: TRUSTED_PROXIES=127.0.0.1
     * - Multiple: TRUSTED_PROXIES=127.0.0.1,10.0.0.0/8
     * - All proxies: TRUSTED_PROXIES=*
     * - Cloudflare: TRUSTED_PROXIES=173.245.48.0/20,103.21.244.0/22,...
     */
    protected $proxies;

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

    public function __construct()
    {
        $this->proxies = $this->getTrustedProxies();
    }

    /**
     * Get trusted proxies from environment variable.
     *
     * @return array<int, string>|string|null
     */
    protected function getTrustedProxies(): array|string|null
    {
        $proxies = env('TRUSTED_PROXIES');

        if ($proxies === null || $proxies === '') {
            // Default: trust all (safe for most shared hosting; adjust for production)
            return '*';
        }

        if ($proxies === '*') {
            return '*';
        }

        // Parse comma-separated list
        return array_map('trim', explode(',', $proxies));
    }
}
