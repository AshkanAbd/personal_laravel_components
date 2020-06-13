<?php

namespace App\Component\Tools;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use ReflectionObject;

trait BindWithTrash
{
    private $withTrashFunctions = [];

    /**
     * Check if request route in withTrashRoute or withTrashNamespace array
     *
     * @return boolean
     */
    public function isWithTrashRoute()
    {
        $route = request()->route();
        if (property_exists($this, 'withTrashRoutes') && is_array($this->withTrashRoutes)) {
            $controllerMethod = str_replace($route->getAction('namespace') . '\\', '', $route->getAction('uses'));
            return in_array($controllerMethod, $this->withTrashRoutes);
        }
        if (property_exists($this, 'withTrashNamespaces') && is_array($this->withTrashNamespaces)) {
            return in_array((new ReflectionObject($route->getController()))->getNamespaceName(), $this->withTrashNamespaces);
        }
        return false;
    }

    protected function initializeBindWithTrash()
    {
        if (method_exists($this, 'addWithTrashFunctions')) {
            forward_static_call([$this, 'addWithTrashFunctions']);
        }
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
        $result = $this->bindWithTrashResolver($value);
        if (!$result) return null;
        return $this->runCheckWithTrashFunctionsFunctions($result) ? $result : null;
    }

    public function resolveRouteBindingTrash($value)
    {
        return $this->withTrashed()->where($this->getRouteKeyName(), $value)->first();
    }

    private function bindWithTrashResolver($value)
    {
        if (in_array(SoftDeletes::class, class_uses_recursive($this)) && $this->isWithTrashRoute()) {
            return $this->resolveRouteBindingTrash($value);
        }
        return parent::resolveRouteBinding($value);
    }

    /**
     * @param mixed $result
     * @return bool|mixed
     */
    protected function runCheckWithTrashFunctionsFunctions($result)
    {
        if (property_exists($this, 'withTrashFunctions') && is_array($this->withTrashFunctions)) {
            if (sizeof($this->withTrashFunctions) == 0) {
                return true;
            }
            foreach ($this->withTrashFunctions as $function) {
                if ($function instanceof Closure) {
                    if (call_user_func($function, $result, request())) return true;
                }
            }
            return false;
        }
        return true;
    }
}