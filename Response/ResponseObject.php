<?php

namespace App\Component\Response;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Traits\Macroable;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResponseObject extends JsonResponse implements ResponseFactory
{
    use Macroable;

    public static $NOT_FOUND = 1;
    public static $NOT_FOUND_MSG = 2;
    public static $BAD_REQUEST = 3;
    public static $BAD_REQUEST_MSG = 4;
    public static $PERMISSION_DENIED = 5;
    public static $PERMISSION_DENIED_MSG = 6;
    public static $NO_AUTH = 7;
    public static $OK = 8;
    public static $OK_MSG = 9;
    public static $OTHER = 10;

    /**
     * The view factory instance.
     *
     * @var Factory
     */
    protected $view;

    /**
     * The redirector instance.
     *
     * @var Redirector
     */
    protected $redirector;

    /**
     * @var int
     */
    public $type;
    /**
     * @var mixed
     */
    public $data;
    /**
     * @var string
     */
    public $msg;
    /**
     * @var string
     */
    public $statusMessage;

    /**
     * ResponseObject constructor.
     * @param $type
     * @param $data
     * @param $msg
     * @param string $statusMessage
     */
    public function __construct($type, $data, $msg, $statusMessage = '')
    {
        parent::__construct('', 200, []);
        $this->type = $type;
        $this->data = $data;
        $this->msg = $msg;
        $this->statusMessage = $statusMessage;
    }

    /**
     * @param int $type
     * @param mixed $data
     * @param string $msg
     * @param string $statusMessage
     * @return ResponseObject
     */
    public static function dataType($type, $data = null, $msg = null, $statusMessage = '')
    {
        return new ResponseObject($type, $data, $msg, $statusMessage);
    }

    /**
     * @param int $type
     * @param string $msg
     * @param string $statusMessage
     * @return ResponseObject
     */
    public static function msgType($type, $msg = null, $statusMessage = '')
    {
        return new ResponseObject($type, null, $msg, $statusMessage);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '{ status = ' . $this->type . ', message = ' . $this->msg . ', data = ' . json_encode($this->data) . ' }';
    }

    /**
     * Create a new response instance.
     *
     * @param string $content
     * @param int $status
     * @param array $headers
     * @return ResponseObject|Response
     */
    public function make($content = '', $status = 200, array $headers = [])
    {
        return new Response($content, $status, $headers);
    }

    /**
     * Create a new "no content" response.
     *
     * @param int $status
     * @param array $headers
     * @return \App\Component\Response\ResponseObject
     */
    public function noContent($status = 204, array $headers = [])
    {
        return $this->make('', $status, $headers);
    }

    /**
     * Create a new response for a given view.
     *
     * @param string|array $view
     * @param array $data
     * @param int $status
     * @param array $headers
     * @return \App\Component\Response\ResponseObject
     */
    public function view($view, $data = [], $status = 200, array $headers = [])
    {
        if (is_array($view)) {
            return $this->make($this->view->first($view, $data), $status, $headers);
        }

        return $this->make($this->view->make($view, $data), $status, $headers);
    }

    /**
     * Create a new JSON response instance.
     *
     * @param mixed $data
     * @param int $status
     * @param array $headers
     * @param int $options
     */
    public function json($data = [], $status = 200, array $headers = [], $options = 0, bool $json = false)
    {
        $this->encodingOptions = $options;

        $this->headers = new ResponseHeaderBag($headers);
        $this->setStatusCode($status);
        $this->setProtocolVersion('1.0');

        if (null === $data) {
            $data = new \ArrayObject();
        }
        $json ? $this->setJson($data) : $this->setData($data);
    }

    /**
     * Create a new JSONP response instance.
     *
     * @param string $callback
     * @param mixed $data
     * @param int $status
     * @param array $headers
     * @param int $options
     * @return \Illuminate\Http\JsonResponse
     */
    public function jsonp($callback, $data = [], $status = 200, array $headers = [], $options = 0)
    {
        return $this->json($data, $status, $headers, $options)->setCallback($callback);
    }

    /**
     * Create a new streamed response instance.
     *
     * @param \Closure $callback
     * @param int $status
     * @param array $headers
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function stream($callback, $status = 200, array $headers = [])
    {
        return new StreamedResponse($callback, $status, $headers);
    }

    /**
     * Create a new streamed response instance as a file download.
     *
     * @param \Closure $callback
     * @param string|null $name
     * @param array $headers
     * @param string|null $disposition
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function streamDownload($callback, $name = null, array $headers = [], $disposition = 'attachment')
    {
        $response = new StreamedResponse($callback, 200, $headers);

        if (!is_null($name)) {
            $response->headers->set('Content-Disposition', $response->headers->makeDisposition(
                $disposition,
                $name,
                $this->fallbackName($name)
            ));
        }

        return $response;
    }

    /**
     * Create a new file download response.
     *
     * @param \SplFileInfo|string $file
     * @param string|null $name
     * @param array $headers
     * @param string|null $disposition
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($file, $name = null, array $headers = [], $disposition = 'attachment')
    {
        $response = new BinaryFileResponse($file, 200, $headers, true, $disposition);

        if (!is_null($name)) {
            return $response->setContentDisposition($disposition, $name, $this->fallbackName($name));
        }

        return $response;
    }

    /**
     * Convert the string to ASCII characters that are equivalent to the given name.
     *
     * @param string $name
     * @return string
     */
    protected function fallbackName($name)
    {
        return str_replace('%', '', Str::ascii($name));
    }

    /**
     * Return the raw contents of a binary file.
     *
     * @param \SplFileInfo|string $file
     * @param array $headers
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function file($file, array $headers = [])
    {
        return new BinaryFileResponse($file, 200, $headers);
    }

    /**
     * Create a new redirect response to the given path.
     *
     * @param string $path
     * @param int $status
     * @param array $headers
     * @param bool|null $secure
     * @return RedirectResponse
     */
    public function redirectTo($path, $status = 302, $headers = [], $secure = null)
    {
        return $this->redirector->to($path, $status, $headers, $secure);
    }

    /**
     * Create a new redirect response to a named route.
     *
     * @param string $route
     * @param array $parameters
     * @param int $status
     * @param array $headers
     * @return RedirectResponse
     */
    public function redirectToRoute($route, $parameters = [], $status = 302, $headers = [])
    {
        return $this->redirector->route($route, $parameters, $status, $headers);
    }

    /**
     * Create a new redirect response to a controller action.
     *
     * @param string $action
     * @param array $parameters
     * @param int $status
     * @param array $headers
     * @return RedirectResponse
     */
    public function redirectToAction($action, $parameters = [], $status = 302, $headers = [])
    {
        return $this->redirector->action($action, $parameters, $status, $headers);
    }

    /**
     * Create a new redirect response, while putting the current URL in the session.
     *
     * @param string $path
     * @param int $status
     * @param array $headers
     * @param bool|null $secure
     * @return RedirectResponse
     */
    public function redirectGuest($path, $status = 302, $headers = [], $secure = null)
    {
        return $this->redirector->guest($path, $status, $headers, $secure);
    }

    /**
     * Create a new redirect response to the previously intended location.
     *
     * @param string $default
     * @param int $status
     * @param array $headers
     * @param bool|null $secure
     * @return RedirectResponse
     */
    public function redirectToIntended($default = '/', $status = 302, $headers = [], $secure = null)
    {
        return $this->redirector->intended($default, $status, $headers, $secure);
    }
}
