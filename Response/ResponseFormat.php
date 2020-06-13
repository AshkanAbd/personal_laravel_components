<?php

namespace App\Component\Response;

class ResponseFormat
{
    /**
     * @param array $response
     * @param null $data
     * @param null $msg
     */
    public static function fill(array &$response, $data = null, $msg = null)
    {
        $response['data'] = $data;
        $response['message'] = $msg;
    }

    /**
     * @return array
     */
    public static function arrayNotFound()
    {
        return [
            'status' => 'error',
            'message' => null,
            'data' => null,
        ];
    }

    /**
     * @param ResponseObject $responseObject
     * @return array
     */
    public static function notFound(ResponseObject $responseObject)
    {
        $array = self::arrayNotFound();
        self::fill($array, $responseObject->data, $responseObject->msg);
        return $array;
    }

    /**
     * @param ResponseObject $responseObject
     * @return array
     */
    public static function notFoundMsg(ResponseObject $responseObject)
    {
        $array = self::arrayNotFound();
        self::fill($array, null, $responseObject->msg);
        return $array;
    }

    /**
     * @return array
     */
    public static function arrayBadRequest()
    {
        return [
            'status' => 'validation-error',
            'message' => null,
            'data' => null,
        ];
    }

    /**
     * @param ResponseObject $responseObject
     * @return array
     */
    public static function badRequest(ResponseObject $responseObject)
    {
        $array = self::arrayBadRequest();
        self::fill($array, $responseObject->data, $responseObject->msg);
        return $array;
    }

    /**
     * @param ResponseObject $responseObject
     * @return array
     */
    public static function badRequestMsg(ResponseObject $responseObject)
    {
        $array = self::arrayBadRequest();
        self::fill($array, null, $responseObject->msg);
        return $array;
    }

    /**
     * @return array
     */
    public static function arrayPermissionDenied()
    {
        return [
            'status' => 'error',
            'message' => null,
            'data' => null,
        ];
    }

    /**
     * @param ResponseObject $responseObject
     * @return array
     */
    public static function permissionDenied(ResponseObject $responseObject)
    {
        $array = self::arrayPermissionDenied();
        self::fill($array, $responseObject->data, $responseObject->msg);
        return $array;
    }

    /**
     * @param ResponseObject $responseObject
     * @return array
     */
    public static function permissionDeniedMsg(ResponseObject $responseObject)
    {
        $array = self::arrayPermissionDenied();
        self::fill($array, null, $responseObject->msg);
        return $array;
    }

    /**
     * @return array
     */
    public static function arrayNotAuth()
    {
        return [
            'status' => 'error',
            'message' => null,
            'data' => null,
        ];
    }

    /**
     * @param ResponseObject $responseObject
     * @return array
     */
    public static function notAuth(ResponseObject $responseObject)
    {
        $array = self::arrayNotAuth();
        self::fill($array, $responseObject->data, $responseObject->msg);
        return $array;
    }

    /**
     * @return array
     */
    public static function arrayOK()
    {
        return [
            'status' => 'success',
            'message' => null,
            'data' => null,
        ];
    }

    /**
     * @param ResponseObject $responseObject
     * @return array
     */
    public static function ok(ResponseObject $responseObject)
    {
        $array = self::arrayOK();
        self::fill($array, $responseObject->data, $responseObject->msg);
        return $array;
    }

    /**
     * @param ResponseObject $responseObject
     * @return array
     */
    public static function okMsg(ResponseObject $responseObject)
    {
        $array = self::arrayOK();
        self::fill($array, null, $responseObject->msg);
        return $array;
    }

    /**
     * @return array
     */
    public static function testArray()
    {
        return [
            'status',
            'message',
            'data',
        ];
    }

    /**
     * @param array $array
     * @param mixed $data
     * @return array
     */
    public static function testFill(array $array, $data = null): array
    {
        $array['data'] = $data;
        return $array;
    }
}