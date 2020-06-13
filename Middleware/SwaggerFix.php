<?php

namespace App\Component\Middleware;

use Closure;
use \Illuminate\Http\Request;

/**
 * Class SwaggerFix
 * Fix swagger request and change it to something laravel can handle it.
 * Use in first level middleware
 *
 * @package App\Http\Middleware
 */
class SwaggerFix
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $request = $this->fixAuth($request);
        $request = $this->fixBodyArray($request);
        $request->merge($_POST);
        return $next($request);
    }

    /**
     * Fix swagger wrong arrays to laravel array types
     *
     * @param Request $request
     * @return Request
     */
    private function fixBodyArray(Request $request): Request
    {
        if ($request->method() === 'POST') {
            foreach ($_POST as $item => $value) {
                $this->fixArray($value, $item);
            }
        }
        return $request;
    }

    private function fixArray(&$values, ...$keys): void
    {
        if (gettype($values) === 'array') {
            $arr = [];
            foreach ($values as $item => $value) {
                $this->fixArray($value, $keys, $item);
                if (gettype($value) === 'string') {
                    $subStrs = explode(',', $value);
                    foreach ($subStrs as $subStr) {
                        array_push($arr, $subStr);
                    }
                } else {
                    array_push($arr, $value);
                }
            }
            $this->fillArrays($values, $arr);
            $keyString = '';
            foreach ($keys as $key) {
                $keyString .= "['$key']";
            }
            eval('$_POST' . "$keyString" . ' = $values;');
        }
    }

    private function fillArrays(array &$array1, array $array2): void
    {
        while (!empty($array1)) array_pop($array1);
        foreach ($array2 as $item) {
            $array1[] = $item;
        }
    }

    /**
     * Fix swagger auth header to passport auth header
     *
     * @param Request $request
     * @return Request
     */
    private function fixAuth(Request $request)
    {
        if (strpos($request->headers->get("Authorization"), "Bearer ") === false) {
            $request->headers->set("Authorization", "Bearer " . $request->headers->get("Authorization"));
        }
        return $request;
    }
}
