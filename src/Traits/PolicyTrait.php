<?php

namespace Kordy\Auzo\Traits;

trait PolicyTrait
{
    use RefreshesPermissionCache;

    /**
     * Get all permissions that has policies
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions() {
        return $this->belongsToMany(app('AuzoPermission'), 'permission_policy')->withPivot('id', 'operator');
    }

    /**
     * Find policy by its label
     *
     * @param $query
     * @param string $name
     * @return PolicyTrait
     */
    public function scopeFindByName($query, $name)
    {
        return $query->where('name', $name)->first();
    }

    /**
     * Find policy by name or id
     *
     * @param string|integer|object $policy
     * @return bool
     * @internal param $ability
     */
    public function findByNameOrId($policy)
    {
        if (is_string($policy)) {
            return $this->findByName($policy);
        }
        if (is_integer($policy)) {
            return $this->findOrFail($policy);
        }
        if ($policy instanceof $this) {
            return $policy;
        }
        return false;
    }
}