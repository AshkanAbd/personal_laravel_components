<?php

namespace App\Component\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

/**
 * Class Authenticate
 * Change and implement laravel Authenticate middleware
 * Use for passport (I don't check on others)
 * Works with auth:auth, auth:api, auth:admin
 *
 * @package App\Http\Middleware
 */
class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param Request $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }

    public function handle($request, Closure $next, ...$guards)
    {
        if ($this->authenticate($request, $guards)) {
            if (!is_null($request->user()) && !$request->user()->active) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'حساب شما غیرفعال شده است.',
                    'data' => null
                ], 400);
            }
            return $next($request);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'لطفا وارد سیستم شوید.',
                'data' => null
            ], 401);
        }
    }


    protected function authenticate($request, array $guards)
    {
        if (empty($guards)) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            if ($guard === 'api') {
                if ($this->auth->guard($guard)->check() &&
                    $this->auth->guard($guard)->user()->completed) {
                    $this->auth->shouldUse($guard);
                }
                return true;
            } else if ($guard === 'admin') {
                if ($this->auth->guard($guard)->check()) {
                    $this->auth->shouldUse($guard);
                    return true;
                }
                return false;
            } else if ($guard === 'auth') {
                if (!$request->user()) {
                    return false;
                }
                if (!$request->user()->completed) {
                    return false;
                }
                return true;
            } else if ($guard === 'setup') {
                if ($this->auth->guard('api')->check() &&
                    !$this->auth->guard('api')->user()->completed) {
                    $this->auth->shouldUse('api');
                    return true;
                }
                return false;
            }
        }
        return false;
    }
}
