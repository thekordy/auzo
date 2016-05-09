<?php

namespace Kordy\Auzo\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Kordy\Auzo\Traits\PermissionTrait
 */
class AuzoPermissionFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'AuzoPermission';
    }
}
