<?php

use App\Component\Response\ResponseObject;

/*
|------------------------------------------------------------------------
| Bring a feature from laravel 7 to 6
|------------------------------------------------------------------------
*/
if (!function_exists('registerWithoutMiddleware')) {
    /**
     * Register all function without arg as route.
     *
     * @return \Illuminate\Routing\Route
     */
    function registerWithoutMiddleware()
    {
        \Illuminate\Routing\Route::macro('withoutMiddleware', function ($middleware) {
            if (is_string($middleware)) {
                $middleware = func_get_args();
            }

            $this->action['middleware'] = array_diff(
                (array)($this->action['middleware'] ?? []), $middleware
            );

            return $this;
        });
    }
}

/*
|------------------------------------------------------------------------
| Get controller function name that request will call it.
|------------------------------------------------------------------------
*/
if (!function_exists('getRequestTargetFunction')) {
    /**
     * Get target method in for given request in controllers
     *
     * @param $request
     * @return false|string
     */
    function getRequestTargetFunction($request)
    {
        return substr($request->route()->getAction('uses'), strpos($request->route()->getAction('uses'), '@') + 1);
    }
}

/*
|------------------------------------------------------------------------
| Still in develop faze...
|------------------------------------------------------------------------
*/
if (!function_exists('registerRoutes')) {
    /**
     * Register all function without arg as route.
     *
     * @param array $scope
     * @throws Exception
     */
    function registerRoutes($scope)
    {
        throw new Exception("Still in develop faze...");
        foreach ($scope as $item => $value) {
            if ($value instanceof Closure) {
                try {
                    $ref = new ReflectionFunction($value);
                    if ($ref->getNumberOfParameters() === 0) {
                        $value();
                    }
                } catch (Exception $e) {
                }
            }
        }
    }
}


/*
|------------------------------------------------------------------------
| Get authenticated user.
|------------------------------------------------------------------------
*/
if (!function_exists('user')) {
    /**
     * Get current login user
     *
     * @return mixed
     */
    function user()
    {
        return request()->user();
    }
}

/*
|------------------------------------------------------------------------
| Controller Extension methods
|------------------------------------------------------------------------
*/
if (!function_exists('monthString')) {
    /**
     * Convert month int to jalali
     *
     * @param int $month
     * @return string
     */
    function monthString($month)
    {
        switch ($month) {
            case 1:
                return 'فروردین';
            case 2:
                return 'اردیبهشت';
            case 3:
                return 'خرداد';
            case 4:
                return 'تیر';
            case 5:
                return 'مرداد';
            case 6:
                return 'شهریور';
            case 7:
                return 'مهر';
            case 8:
                return 'آبان';
            case 9:
                return 'آذر';
            case 10:
                return 'دی';
            case 11:
                return 'بهمن';
            case 12:
                return 'اسفند';
            default:
                return 'نامعتبر';
        }
    }
}

if (!function_exists('notFound')) {
    /**
     * @param mixed $data
     * @param string $msg
     * @return ResponseObject
     */
    function notFound($data = null, $msg = 'داده پیدا نشد.')
    {
        return ResponseObject::dataType(ResponseObject::$NOT_FOUND, $data, $msg);
    }
}

if (!function_exists('notFoundMsg')) {
    /**
     * @param string $msg
     * @return ResponseObject
     */
    function notFoundMsg($msg)
    {
        return ResponseObject::msgType(ResponseObject::$NOT_FOUND_MSG, $msg);
    }
}

if (!function_exists('badRequest')) {
    /**
     * @param mixed $data
     * @param string $msg
     * @return ResponseObject
     */
    function badRequest($data = null, $msg = null)
    {
        return ResponseObject::dataType(ResponseObject::$BAD_REQUEST, $data, $msg);
    }
}

if (!function_exists('badRequestMsg')) {
    /**
     * @param string $msg
     * @return ResponseObject
     */
    function badRequestMsg($msg)
    {
        return ResponseObject::msgType(ResponseObject::$BAD_REQUEST_MSG, $msg);
    }
}

if (!function_exists('permissionDenied')) {
    /**
     * @param mixed $data
     * @param string $msg
     * @return ResponseObject
     */
    function permissionDenied($data = null, $msg = 'مجاز به این کار نیستید.')
    {
        return ResponseObject::dataType(ResponseObject::$PERMISSION_DENIED, $data, $msg);
    }
}

if (!function_exists('permissionDeniedMsg')) {
    /**
     * @param string $msg
     * @return ResponseObject
     */
    function permissionDeniedMsg($msg)
    {
        return ResponseObject::msgType(ResponseObject::$PERMISSION_DENIED_MSG, $msg);
    }
}

if (!function_exists('notAuth')) {
    /**
     * @param mixed $data
     * @param string $msg
     * @return ResponseObject
     */
    function notAuth($data = null, $msg = 'لطفا وارد سیستم شوید.')
    {
        return ResponseObject::dataType(ResponseObject::$NO_AUTH, $data, $msg);
    }
}

if (!function_exists('ok')) {
    /**
     * @param mixed $data
     * @param string $msg
     * @return ResponseObject
     */
    function ok($data = null, $msg = null)
    {
        return ResponseObject::dataType(ResponseObject::$OK, $data, $msg);
    }
}

if (!function_exists('okMsg')) {
    /**
     * @param string $msg
     * @return ResponseObject
     */
    function okMsg($msg = null)
    {
        return ResponseObject::msgType(ResponseObject::$OK_MSG, $msg);
    }
}
