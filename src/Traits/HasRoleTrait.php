<?php

namespace Kordy\Auzo\Traits;

use AuzoRole;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasRoleTrait
{
    /**
     * Get all of the roles for the user.
     *
     * @return BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(app('AuzoRole'), 'role_id');
    }

    /**
     * Add the given role to the user.
     *
     * @param string|AuzoRole $role
     *
     * @return $this
     */
    public function assignRole($role)
    {
        $this->role_id = $this->getRoleId($role);
        $this->save();

        return $this;
    }

    /**
     * Revoke the given role from the user.
     *
     * @return mixed
     */
    public function removeRole()
    {
        $this->role_id = null;
        $this->save();

        return $this;
    }

    /**
     * Determine if the user has any of the given role(s).
     *
     * @param string|array|AuzoRole|\Illuminate\Support\Collection $roles
     *
     * @return bool
     */
    public function hasRole($roles)
    {
        if ($user_role = $this->role) {
            // given a role name to check
            if (is_string($roles)) {
                return $user_role->name == $roles;
            }

            $role = app('AuzoRole');
            // given a role instance to check
            if ($roles instanceof $role) {
                return $user_role->id == $roles->id;
            }
            // given array of roles to check
            if (is_array($roles)) {
                foreach ($roles as $role) {
                    if ($this->hasRole($role)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * check permission inheritance through roles.
     *
     * @param $ability
     *
     * @return bool
     */
    public function hasPermissionTo($ability)
    {
        if ($role = $this->role) {
            return $role->hasPermissionTo($ability);
        }

        return false;
    }

    /**
     * check if has capability via inherited from role.
     *
     * @param $ability
     * @param null $model
     *
     * @return bool
     */
    public function isCapableTo($ability, $model = null)
    {
        if ($role = $this->role) {
            return $role->isCapableTo($ability, $model);
        }

        return false;
    }

    /**
     * @param $role
     *
     * @throws \Exception
     *
     * @return int
     */
    protected function getRoleId($role)
    {
        if (is_string($role)) {
            return AuzoRole::findByName($role)->first()->id;
        }
        $role_inst = app('AuzoRole');
        if ($role instanceof $role_inst) {
            return $role->id;
        }
        if (is_int($role)) {
            return $role;
        }
        throw new \Exception("Could not extract a valid role id from $role");
    }
}
