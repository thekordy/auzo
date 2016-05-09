<?php

namespace Kordy\Auzo\Traits;

trait AbilityTrait
{
    use RefreshesPermissionCache;

    /**
     * Get all assigned abilities to users
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions()
    {
        return $this->hasMany(app('AuzoPermission'), 'ability_id');
    }

    /**
     * Find Ability by its name
     *
     * @param $query
     * @param string $name
     * @return AbilityTrait
     */
    public function scopeFindByName($query, $name) 
    {
        return $query->where('name', $name)->first();
    }

    /**
     * Find ability by name or id
     * 
     * @param string|integer|object $ability
     * @return bool
     */
    public function findByNameOrId($ability)
    {
        if (is_string($ability)) {
            return $this->findByName($ability);
        }
        if (is_integer($ability)) {
            return $this->find($ability);
        }
        if ($ability instanceof $this) {
            return $ability;
        }
        return false;
    }
}