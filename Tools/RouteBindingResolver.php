<?php

namespace App\Component\Tools;

trait RouteBindingResolver
{
    use BindWithTrash, BindWithUserPolicy {
        BindWithTrash::resolveRouteBinding as withTrashResolver;
        BindWithUserPolicy::resolveRouteBinding as userPolicyResolver;
    }

    public function resolveRouteBinding($value)
    {
        return $this->userPolicyResolver($value);
    }

    protected function bindWithUserPolicyResolver($value)
    {
        if ($this->checkUserPolicy() && request()->user()) {
            return $this->resolveRouteBindingUserPolicy($value);
        }
        return $this->withTrashResolver($value);
    }
}