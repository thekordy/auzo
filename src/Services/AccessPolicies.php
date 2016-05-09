<?php

namespace Kordy\Auzo\Services;

use Illuminate\Http\Request;

class AccessPolicies
{

    /**
     * Example of condition method to restrict permissions
     *
     * @param $user
     * @param $model
     * @return bool
     */
    public function profileOwner($user, $model)
    {
        $id = $user->getKeyName();
        if (! $model instanceof Request) {
            return $user->$id == $model->$id;
        }
        // where $model = Request $request passed by the middleware
        return $user->$id == $model->id;
    }

    /**
     * Example of condition method to restrict permissions
     * 
     * @param $user
     * @return bool
     */
    public function siteAdmin($user)
    {
        $id = $user->getKeyName();
        return $user->$id == 1;
    }
}