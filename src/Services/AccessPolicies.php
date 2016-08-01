<?php

namespace Kordy\Auzo\Services;

use Illuminate\Http\Request;

class AccessPolicies
{
    /**
     * Example of condition method to restrict permissions.
     *
     * @param $ability
     * @param $role
     * @param $user
     * @param $model
     * @return bool
     */
    public function profileOwner($ability, $role, $user, $model)
    {
        $id = $user->getKeyName();
        if (!$model instanceof Request) {
            return $user->$id == $model->$id;
        }
        // where $model = Request $request passed by the middleware
        return $user->$id == $model->id;
    }

    /**
     * Example of condition method to restrict permissions.
     *
     * @param $ability
     * @param $role
     * @param $user
     * @param $model
     * @return bool
     */
    public function siteAdmin($ability, $role, $user, $model)
    {
        $id = $user->getKeyName();

        return $user->$id == 1;
    }
}
