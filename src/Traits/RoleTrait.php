<?php

namespace Kordy\Auzo\Traits;

use Illuminate\Http\Request;
use \Exception;
use AuzoAbility;

trait RoleTrait
{
    use RefreshesPermissionCache;

    /**
     * User relationship
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
     * @param string|integer|array $abilities
     * @return PermissionTrait
     * @throws \Exception
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
     * @param string|integer|array $abilities
     * @return PermissionTrait
     * @throws \Exception
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
     * @return Permission
     * @throws Exception
     */
    protected function createPermission($ability)
    {
        if (! AuzoAbility::findByNameOrId($ability)) {
            throw new Exception('Wrong ability identifier!');
        }
        return $this->permissions()->create(['ability_id' => AuzoAbility::findByNameOrId($ability)->id]);
    }

    /**
     * @param $ability
     * @return bool
     * @throws Exception
     * @internal param $abilities
     */
    protected function removePermission($ability)
    {
        if (! AuzoAbility::findByNameOrId($ability)) {
            throw new Exception('Wrong ability identifier!');
        }
        $this->permissions()->forAbility($ability)->delete();
        return true;
    }

    /**
     * Check if has a permission to ability
     *
     * @param string|integer|array|object $ability
     * @return bool
     */
    public function hasPermissionTo($ability) {
        if (is_array($ability)) {
            return $this->permissionTo($ability)->count() === count($ability);
        }
        return !! $this->permissionTo($ability)->first();
    }

    /**
     * Get the permission instance to an ability
     *
     * @param $ability
     * @return mixed
     */
    public function permissionTo($ability)
    {
        return $this->permissions()->forAbility($ability);
    }

    /**
     * Similar to hasPermissionTo but it also checking all of the permission policies
     *
     * @param string|integer|object $ability
     * @param null|object $model
     * @return bool
     */
    public function isCapableTo($ability, $model = null)
    {
        if (! $this->hasPermissionTo($ability)) {
            return false;
        }

        $policies = $this->permissions()->forAbility($ability)->policies;

        if ($policies->isEmpty()) {
            return true;
        }

        return $this->processPermissionPolicies($policies, $model);
    }

    /**
     * apply policies of permission on a given model
     *
     * @param $model
     * @param $policies
     * @return bool
     */
    protected function processPermissionPolicies($policies, $model)
    {
        $result = true;

        foreach ($policies as $policy) {
            list($class, $method) = explode('@', $policy->method);
            $policy_method = app($class)->$method($this, $model);
            if ($policy->pivot->operator == 'or' || $policy->pivot->operator == '||') {
                $result = $result || $policy_method;
            } else {
                $result = $result && $policy_method;
            }
        }

        return $result;
    }

    /**
     * Find Role by its name
     * 
     * @param $query
     * @param string $name
     */
    public function scopeFindByName($query, $name) 
    {
        $query->where('name', $name);
    }
}