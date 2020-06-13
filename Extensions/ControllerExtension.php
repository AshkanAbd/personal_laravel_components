<?php

namespace App\Component\Extensions;

use App\Component\Response\ResponseObject;
use Illuminate\Database\Eloquent\Builder;

trait ControllerExtension
{
    use SearchExtension, UploadExtension, ValidationExtension, PaginationExtension;

    /**
     * @param mixed $data
     * @param string $msg
     * @return ResponseObject
     */
    protected function notFound($data = null, $msg = 'داده پیدا نشد.')
    {
        return ResponseObject::dataType(ResponseObject::$NOT_FOUND, $data, $msg);
    }

    /**
     * @param string $msg
     * @return ResponseObject
     */
    protected function notFoundMsg($msg)
    {
        return ResponseObject::msgType(ResponseObject::$NOT_FOUND_MSG, $msg);
    }

    /**
     * @param mixed $data
     * @param string $msg
     * @return ResponseObject
     */
    protected function badRequest($data = null, $msg = null)
    {
        return ResponseObject::dataType(ResponseObject::$BAD_REQUEST, $data, $msg);
    }

    /**
     * @param string $msg
     * @return ResponseObject
     */
    protected function badRequestMsg($msg)
    {
        return ResponseObject::msgType(ResponseObject::$BAD_REQUEST_MSG, $msg);
    }

    /**
     * @param mixed $data
     * @param string $msg
     * @return ResponseObject
     */
    protected function permissionDenied($data = null, $msg = 'مجاز به این کار نیستید.')
    {
        return ResponseObject::dataType(ResponseObject::$PERMISSION_DENIED, $data, $msg);
    }

    /**
     * @param string $msg
     * @return ResponseObject
     */
    protected function permissionDeniedMsg($msg)
    {
        return ResponseObject::msgType(ResponseObject::$PERMISSION_DENIED_MSG, $msg);
    }

    /**
     * @param mixed $data
     * @param string $msg
     * @return ResponseObject
     */
    protected function notAuth($data = null, $msg = 'لطفا وارد سیستم شوید.')
    {
        return ResponseObject::dataType(ResponseObject::$NO_AUTH, $data, $msg);
    }

    /**
     * @param mixed $data
     * @param string $msg
     * @return ResponseObject
     */
    protected function ok($data = null, $msg = null)
    {
        return ResponseObject::dataType(ResponseObject::$OK, $data, $msg);
    }

    /**
     * @param string $msg
     * @return ResponseObject
     */
    protected function okMsg($msg = null)
    {
        return ResponseObject::msgType(ResponseObject::$OK_MSG, $msg);
    }
}