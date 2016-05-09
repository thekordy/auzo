<?php

namespace Kordy\Auzo\Traits;

use AuzoPermissionRegistrar;

trait RefreshesPermissionCache
{
    public static function bootRefreshesPermissionCache()
    {
        static::created(function ($model) {
            $model->forgetCachedPermissions();
        });

        static::updated(function ($model) {
            $model->forgetCachedPermissions();
        });

        static::deleted(function ($model) {
            $model->forgetCachedPermissions();
        });
    }

    /**
     *  Forget the cached Abilities.
     */
    public function forgetCachedPermissions()
    {
        AuzoPermissionRegistrar::forgetCachedPermissions();
    }
}
