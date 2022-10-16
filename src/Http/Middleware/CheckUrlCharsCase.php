<?php

namespace AttractCores\LaravelCoreKit\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUrlCharsCase
{

    /**
     * The URIs that should be excluded from url language verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];

    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param null     $guard
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = NULL)
    {
        if ( $request->isMethod('get') ) {
            if ( $this->checkExceptions($request) ) {
                return $next($request);
            }

            $url = $request->path();

            if ( preg_match('/[A-Z]/', $url) ) {
                return redirect()->to(sprintf(
                        '%s%s',
                        mb_strtolower($request->path()),
                        ! empty($request->getQueryString()) ? '?' . $request->getQueryString() : '')
                )->setStatusCode(301);
            } elseif ( preg_match('/\/\//', $url) ) {
                return redirect()->to(sprintf(
                        '%s%s',
                        preg_replace('/\/\//', "/", $request->path()),
                        ! empty($request->getQueryString()) ? '?' . $request->getQueryString() : '')
                )->setStatusCode(301);
            }
        }

        return $next($request);
    }

    /**
     * Check if url excepted.
     *
     * @param Request $request
     *
     * @return bool
     */
    protected function checkExceptions(Request $request)
    {
        foreach ( $this->except as $item ) {
            if ( $request->is($item) ) {
                return true;
            }
        }

        return false;
    }

}
