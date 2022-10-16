<?php

namespace App\Http\Middleware;

use Fideloper\Proxy\TrustProxies as Middleware;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array|string|null
     */
    protected $proxies;

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_HOST |
                         Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO;

    /**
     * Create a new trusted proxies middleware instance.
     *
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        parent::__construct($config);

        // Add trusted proxies.
        if ( ! app()->environment([ 'local', 'testing' ]) && $ip = $config[ 'project' ][ 'trusted_proxy_ip' ] ) {
            $this->proxies = [ $ip ];
        }

        // Enable AWS ELB forwarding
        if ( $config[ 'project' ][ 'aws_elb_enabled' ] ) {
            $this->headers = Request::HEADER_X_FORWARDED_AWS_ELB;
        }
    }

}
