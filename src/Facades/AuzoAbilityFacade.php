<?php

namespace Kordy\Auzo\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Kordy\Auzo\Traits\AbilityTrait
 */
class AuzoAbilityFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'AuzoAbility';
    }
}
