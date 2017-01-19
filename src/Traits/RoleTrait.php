<?php

namespace Kordy\Auzo\Traits;

use AuzoAbility;
use Exception;

trait RoleTrait
{
    use RefreshesPermissionCache;

    /**
     * User relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(app('AuzoUser'), 'role_id');
    }

    /**
     * Get all of the abilities for the role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions()
    {
        return $this->hasMany(app('AuzoPermission'), 'role_id');
    }

    /**
     * Grant permission to the given ability.
     *
     * @param string|int|array $abilities
     *
     * @throws \Exception
     *
     * @return PermissionTrait
     */
    public function givePermissionTo($abilities)
    {
        if (is_array($abilities)) {
            foreach ($abilities as $ability) {
                $permissions[] = $this->createPermission($ability);
            }

            return $permissions;
        }

        return $this->createPermission($abilities);
    }

    /**
     * Remove permission to the given ability.
     *
     * @param string|int|array $abilities
     *
     * @throws \Exception
     *
     * @return PermissionTrait
     *
     * @internal param $ability
     */
    public function removePermissionTo($abilities)
    {
        if (is_array($abilities)) {
            foreach ($abilities as $ability) {
                $this->removePermission($ability);
            }

            return true;
        }

        return $this->removePermission($abilities);
    }

    /**
     * @param $ability
     *
     * @throws Exception
     *
     * @return Permission
     */
    protected function createPermission($ability)
    {
        if (!AuzoAbility::findByNameOrId($ability)) {
            throw new Exception('Wrong ability identifier!');
        }

        return $this->permissions()->create(['ability_id' => AuzoAbility::findByNameOrId($ability)->id]);
    }

    /**
     * @param $ability
     *
     * @throws Exception
     *
     * @return bool
     *
     * @internal param $abilities
     */
    protected function removePermission($ability)
    {
        if (!AuzoAbility::findByNameOrId($ability)) {
            throw new Exception('Wrong ability identifier!');
        }
        $this->permissions()->forAbility($ability)->delete();

        return true;
    }

    /**
     * Check if has a permission to ability.
     *
     * @param string|int|array|object $ability
     *
     * @return bool
     */
    public function hasPermissionTo($ability)
    {
        if (is_array($ability)) {
            return $this->permissionTo($ability)->count() === count($ability);
        }

        return $this->permissionTo($ability);
    }

    /**
     * Get the permission instance to an ability.
     *
     * @param $ability
     *
     * @return mixed
     */
    public function permissionTo($ability)
    {
        $ability = AuzoAbility::findByNameOrId($ability);

        if ($ability) {
            foreach ($ability->permissions as $permission) {
                if ($permission->role_id === $this->id) {
                    return $permission;
                }
            }
        }

        return false;
    }

    /**
     * Similar to hasPermissionTo but it also checking all of the permission policies.
     *
     * @param string|int|object $ability
     * @param $user
     * @param null|object $model
     * @return bool
     */
    public function isCapableTo($ability, $user, $model = null)
    {
        if (!$permission = $this->hasPermissionTo($ability)) {
            return false;
        }

        $policies = $permission->policies;

        if (empty($policies)) {
            return true;
        }

        return $this->processPermissionPolicies($policies, $ability, $user, $model);
    }

    /**
     * apply policies of permission on a given model.
     *
     * @param $policies
     *
     * @param $ability
     * @param $user
     * @param $model
     * @return bool
     */
    protected function processPermissionPolicies($policies, $ability, $user, $model)
    {
        $result = true;

        foreach ($policies->sortBy('pivot.id') as $policy) {
            list($class, $method) = explode('@', $policy->method);
            $policy_method = app($class)->$method($ability, $this, $user, $model);
            if ($policy->pivot->operator == 'or' || $policy->pivot->operator == '||') {
                $result = $result || $policy_method;
            } else {
                $result = $result && $policy_method;
            }
        }

        return $result;
    }

    /**
     * Find Role by its name.
     *
     * @param $query
     * @param string $name
     */
    public function scopeFindByName($query, $name)
    {
        $query->where('name', $name);
    }

    /**
     * Find ability by name or id.
     *
     * @param string|int|object $role
     *
     * @return bool
     */
    public function findByNameOrId($role)
    {
        if (is_string($role)) {
            return $this->findByName($role);
        }
        if (is_int($role)) {
            return $this->findOrFail($role);
        }
        if ($role instanceof $this) {
            return $role;
        }

        return false;
    }
}
