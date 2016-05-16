<?php

namespace Kordy\Auzo\Traits;

trait PolicyTrait
{
    use RefreshesPermissionCache;

    /**
     * Get all permissions that has policies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(app('AuzoPermission'), 'permission_policy')->withPivot('id', 'operator');
    }
}
