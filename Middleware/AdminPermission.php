<?php

namespace App\Component\Middleware;

use App\Admin;
use Closure;
use Illuminate\Http\Request;

/**
 * Class AdminPermission
 * Admin permission for route policy
 * Note: I don't check laravel's route policy.
 *
 * @package App\Http\Middleware
 */
class AdminPermission
{
    /**
     * Handle an incoming request.
     * allow request only if admin has that permission
     *
     * @param Request $request
     * @param Closure $next
     * @param $param
     * @return mixed
     */
    public function handle($request, Closure $next, $param)
    {
        if (Admin::query()->whereHas('role.rolePermission.permission', function ($q) use ($param) {
            $q->where('code', $param);
        })->exists()) {
            return $next($request);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'شما به این قسمت دسترسی ندارید.',
                'data' => null
            ], 403);
        }
    }
}
