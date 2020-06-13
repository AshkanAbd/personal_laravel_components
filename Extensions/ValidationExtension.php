<?php

namespace App\Component\Extensions;

use App\Component\Tools\Validation;
use Exception;
use Illuminate\Validation\ValidationException;
use Validator;

trait ValidationExtension
{
    /**
     * @var string for filter messaging
     */
    private $namespace = 'App\Http\Controllers';

    /**
     * Load class and function of Target controller
     *
     * @param string $controllerMethod
     * @return array
     */
    protected function loadClassFunction($controllerMethod = '')
    {
        $callerClass = null;
        $callerFunction = null;
        $prefix = null;
        if (strpos($controllerMethod, '@') !== false) {
            $subStr = explode('@', $controllerMethod);
            $callerClass = $subStr[0];
            $callerFunction = $subStr[1];
            if (class_basename($callerClass) !== $callerClass) {
                $callerClass = substr(str_replace($this->namespace, '', $callerClass), 1);
                if ($pos = strpos($callerClass, '\\')) {
                    $prefix = substr($callerClass, 0, $pos);
                    $callerClass = $prefix . substr($callerClass, $pos + 1);
                }
            }
        } else if ($controllerMethod === '') {
            $e = new Exception();
            $caller = $e->getTrace()[3];
            $callerFunction = $caller['function'];
            $callerClass = substr(str_replace($this->namespace, '', $caller['class']), 1);
            if ($pos = strpos($callerClass, '\\')) {
                $prefix = substr($callerClass, 0, $pos);
                $callerClass = $prefix . substr($callerClass, $pos + 1);
            }
        }
        return [$callerClass, $callerFunction, $prefix];
    }

    /**
     * Validate incoming request
     *
     * @param string $controllerMethod
     * @return Validation
     * @throws ValidationException
     */
    protected function validate($controllerMethod = '')
    {
        $validation = Validator::make(request()->all(), $this->getRule($controllerMethod),
            $this->getMessage($controllerMethod), $this->getAttribute($controllerMethod));
        if ($validation->fails()) {
            throw new ValidationException($validation, $this->badRequest($validation->errors()->messages()));
        }
        return new Validation($validation->validated());
    }

    /**
     * Get controller's method messages
     *
     * @param string $controllerMethod
     * @return array
     */
    protected function getMessage($controllerMethod = '')
    {
        [$callerClass, $callerFunction, $prefix] = $this->loadClassFunction($controllerMethod);
        if (!is_null($callerClass) && !is_null($callerFunction)) {
            return $this->loadMessage($callerClass, $callerFunction, $prefix);
        }
        return [
            '*' => 'درخواست نامعتبر است.',
        ];
    }

    /**
     * Get routes validation rules
     *
     * @param string $controllerMethod
     * @return array
     */
    protected function getRule($controllerMethod = '')
    {
        [$callerClass, $callerFunction, $prefix] = $this->loadClassFunction($controllerMethod);
        if (!is_null($callerClass) && !is_null($callerFunction)) {
            return $this->loadRule($callerClass, $callerFunction, $prefix);
        }
        return [];
    }

    /**
     * Get routes validation attributes
     *
     * @param string $controllerMethod
     * @return array
     */
    protected function getAttribute($controllerMethod = '')
    {
        [$callerClass, $callerFunction, $prefix] = $this->loadClassFunction($controllerMethod);
        if (!is_null($callerClass) && !is_null($callerFunction)) {
            return $this->loadAttribute($callerClass, $callerFunction, $prefix);
        }
        return [];
    }


    /**
     * Load message from Message class using $class as class field and $method as class field array's key
     *
     * @param string $class
     * @param string $method
     * @param string $prefix
     * @return array
     */
    protected function loadMessage($class, $method, $prefix)
    {
        $messageClass = '\App\Http\Validations\Messages\\';
        if (!is_null($prefix)) {
            $messageClass .= "$prefix";
        }
        $messageClass .= "Messages";
        return $this->load($messageClass, $class, $method, [
            '*' => 'درخواست نامعتبر است.',
        ]);
    }

    /**
     * Load rules from Rule class using $class as class field and $method as class field array's key
     *
     * @param string $class
     * @param string $method
     * @param string $prefix
     * @return array
     */
    protected function loadRule($class, $method, $prefix)
    {
        $ruleClass = '\App\Http\Validations\Rules\\';
        if (!is_null($prefix)) {
            $ruleClass .= "$prefix";
        }
        $ruleClass .= "Rules";
        return $this->load($ruleClass, $class, $method);
    }

    /**
     * Load attributes from Attribute class using $class as class field and $method as class field array's key
     *
     * @param string $class
     * @param string $method
     * @param string $prefix
     * @return array
     */
    protected function loadAttribute($class, $method, $prefix)
    {
        $ruleClass = '\App\Http\Validations\Attributes\\';
        if (!is_null($prefix)) {
            $ruleClass .= "$prefix";
        }
        $ruleClass .= "Attributes";
        return $this->load($ruleClass, $class, $method);
    }

    /**
     * @param string $baseClass
     * @param string $class
     * @param string $method
     * @param array $default
     * @return array
     */
    protected function load($baseClass, $class, $method, $default = [])
    {
        if (class_exists($baseClass)) {
            if (property_exists($baseClass, $class)) {
                $controllerRule = $baseClass::${$class};
                if (key_exists($method, $controllerRule)) {
                    return $controllerRule[$method];
                }
            }
            if (method_exists($baseClass, $class)) {
                $controllerRule = forward_static_call([$baseClass, $class], request());
                if (key_exists($method, $controllerRule)) {
                    return $controllerRule[$method];
                }
            }
        }
        return $default;
    }
}
