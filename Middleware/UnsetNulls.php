<?php

namespace App\Component\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class UnsetNulls
 * Unset null params in request
 *
 * @package App\Http\Middleware
 */
class UnsetNulls
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $params = $request->request;
        $this->remove($params);
        return $next($request);
    }

    /**
     * @param $params
     * @return array|ParameterBag
     */
    protected function remove(&$params)
    {
        foreach ($params as $param => $value) {
            if ($value === null) {
                if ($params instanceof ParameterBag) {
                    $params->remove($param);
                }
                if (is_array($params)) {
                    unset($params[$param]);
                }
            }
            if (is_array($value)) {
                $v = $this->remove($value);
                if ($params instanceof ParameterBag) {
                    $params->set($param, $v);
                }
                if (is_array($params)) {
                    $params[$param] = $v;
                }
            }
        }
        return $params;
    }
}
