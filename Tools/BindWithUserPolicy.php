<?php

namespace App\Component\Tools;

use Closure;
use Illuminate\Database\Eloquent\Model;
use ReflectionObject;

trait BindWithUserPolicy
{
    private $userPolicyFunctions = [];

    public function checkUserPolicy()
    {
        $route = request()->route();
        if (property_exists($this, 'userPolicyRoutes') && is_array($this->userPolicyRoutes)) {
            $controllerMethod = str_replace($route->getAction('namespace') . '\\', '', $route->getAction('uses'));
            if (in_array($controllerMethod, $this->userPolicyRoutes)) {
                return true;
            }
        }
        if (property_exists($this, 'userPolicyNamespaces') && is_array($this->userPolicyNamespaces)) {
            if (in_array((new ReflectionObject($route->getController()))->getNamespaceName(), $this->userPolicyNamespaces)) {
                return true;
            }
        }
        return false;
    }

    protected function initializeBindWithUserPolicy()
    {
        if (method_exists($this, 'addUserPolicyFunctions')) {
            forward_static_call([$this, 'addUserPolicyFunctions']);
        }
        if (!property_exists($this, 'userPolicyColumn')) {
            $this->userPolicyColumn = 'user_id';
        }
    }

    protected static function bootBindWithUserPolicy()
    {
        $unsetCaller = 'unset($model->userPolicyColumn);';
        static::saving(function ($model) use ($unsetCaller) {
            eval($unsetCaller);
        });
        static::creating(function ($model) use ($unsetCaller) {
            eval($unsetCaller);
        });
        static::updating(function ($model) use ($unsetCaller) {
            eval($unsetCaller);
        });
    }

    /**
     * Retrieve the model for a bound value.
     * If request route in bind with trashes then include soft deleted records
     *
     * @param mixed $value
     * @return Model|null
     */
    public function resolveRouteBinding($value)
    {
        $result = $this->bindWithUserPolicyResolver($value);
        if (!$result) return null;
        return $this->runCheckUserPolicyFunctions($result) ? $result : null;
    }

    protected function resolveRouteBindingUserPolicy($value)
    {
        return $this->where($this->getRouteKeyName(), $value)->where($this->userPolicyColumn, request()->user()->id)->first();
    }

    protected function bindWithUserPolicyResolver($value)
    {
        if ($this->checkUserPolicy() && request()->user()) {
            return $this->resolveRouteBindingUserPolicy($value);
        }
        return parent::resolveRouteBinding($value);
    }

    /**
     * @param mixed $result
     * @return bool|mixed
     */
    protected function runCheckUserPolicyFunctions($result)
    {
        if (property_exists($this, 'userPolicyFunctions') && is_array($this->userPolicyFunctions)) {
            if (sizeof($this->userPolicyFunctions) == 0) {
                return true;
            }
            foreach ($this->userPolicyFunctions as $function) {
                if ($function instanceof Closure) {
                    if (call_user_func($function, $result, request())) return true;
                }
            }
            return false;
        }
        return true;
    }
}