<?php

namespace Kordy\Auzo\Traits;

use AuzoAbility;

trait PermissionTrait
{
    use RefreshesPermissionCache;
    
    public function role()
    {
        return $this->belongsTo(app('AuzoRole'), 'role_id');
    }
    
    public function ability()
    {
        return $this->belongsTo(app('AuzoAbility'), 'ability_id');
    }

    /**
     * Get all related policies
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function policies()
    {
        return $this->belongsToMany(app('AuzoPolicy'), 'permission_policy')->withPivot('id', 'operator');
    }

    /**
     * Scope permissions query for specific abilities
     *
     * @param $query
     * @param string|integer|array $abilities
     * @return PermissionTrait
     */
    public function scopeForAbility($query, $abilities)
    { 
        if (is_array($abilities)) {
            $abilities_ids = array_map(function($ability) {
                return AuzoAbility::findByNameOrId($ability)->id;
            }, $abilities);
            return $query->whereIn('ability_id', $abilities_ids)->get();
        } else {
            return $query->where('ability_id', AuzoAbility::findByNameOrId($abilities)->id)->first();
        }
    }
    
    public function addPolicy($policy)
    {
        $this->policies()->attach($policy);
        return $this;
    }
    
    public function removePolicy($policy)
    {
        $this->policies()->detach($policy);
        return $this;
    }
}