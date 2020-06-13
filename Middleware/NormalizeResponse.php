<?php

namespace App\Component\Middleware;

use App\Component\Response\ResponseFormat;
use App\Component\Response\ResponseObject;
use Closure;
use Exception;
use Illuminate\Http\Request;

/**
 * Class NormalizeResponse
 * Put in last level of middleware takes output of controller and change it to something normal for browsers
 * Only works with ControllerExtension methods
 *
 * @package App\Http\Middleware
 */
class NormalizeResponse
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
        $response = $next($request);
        try {
            switch ($response->type) {
                case ResponseObject::$NOT_FOUND:
                    $response->json(ResponseFormat::notFound($response), 404);
                    break;
                case ResponseObject::$NOT_FOUND_MSG:
                    $response->json(ResponseFormat::notFoundMsg($response), 404);
                    break;
                case ResponseObject::$BAD_REQUEST:
                    $response->json(ResponseFormat::badRequest($response), 422);
                    break;
                case ResponseObject::$BAD_REQUEST_MSG:
                    $response->json(ResponseFormat::badRequestMsg($response), 422);
                    break;
                case ResponseObject::$PERMISSION_DENIED:
                    $response->json(ResponseFormat::permissionDenied($response), 403);
                    break;
                case ResponseObject::$PERMISSION_DENIED_MSG:
                    $response->json(ResponseFormat::permissionDeniedMsg($response), 403);
                    break;
                case ResponseObject::$NO_AUTH:
                    $response->json(ResponseFormat::notAuth($response), 401);
                    break;
                case ResponseObject::$OK:
                    $response->json(ResponseFormat::ok($response), 200);
                    break;
                case ResponseObject::$OK_MSG:
                    $response->json(ResponseFormat::okMsg($response), 200);
                    break;
                case ResponseObject::$OTHER:
                default:
                    $response->json([
                        'status' => $response->statusMessage,
                        'message' => $response->msg,
                        'data' => $response->data,
                    ]);
                    break;
            }
        } catch (Exception $e) {
        }
        return $response;
    }
}
