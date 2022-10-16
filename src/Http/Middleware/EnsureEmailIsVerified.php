<?php

namespace AttractCores\LaravelCoreKit\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;

/**
 * Class EnsureEmailIsVerified
 *
 * @version 1.0.0
 * @date    2019-03-10
 * @author  Yure Nery <yurenery@gmail.com>
 */
class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param null                     $guard
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $guard = NULL)
    {
        $user = $request->user($guard);

        if (
            ! $user ||
            ( $user instanceof MustVerifyEmail &&
              ! $user->hasVerifiedEmail() )
        ) {
            abort(403, __('Your email address is not verified.'));
        }

        return $next($request);
    }

}