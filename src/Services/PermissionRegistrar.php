<?php

namespace Kordy\Auzo\Services;

use Exception;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Cache\Repository;
use Log;
use AuzoAbility;

class PermissionRegistrar
{
    /**
     * @var Gate
     */
    protected $gate;

    /**
     * @var Repository
     */
    protected $cache;

    /**
     * @var string
     */
    protected $cacheKey = 'auzo.permissions.cache';

    /**
     * @param Gate       $gate
     * @param Repository $cache
     */
    public function __construct(Gate $gate, Repository $cache)
    {
        $this->gate = $gate;
        $this->cache = $cache;
    }

    /**
     *  Register the abilities.
     *
     * @return bool
     */
    public function registerPermissions()
    {
        try {
            // TODO add configurable before method
            // $this->gate->before(function ($user, $ability) {});

            $this->getPermissions()->map(function ($ability) {

                $this->gate->define($ability->name, function ($user, $model = null) use ($ability) {
                    return $user->isCapableTo($ability, $model);
                });

            });
            // TODO add configurable after method
            // $this->gate->after(function ($user, $ability, $result, $arguments) {});
            return true;
            
        } catch (Exception $e) {
            Log::alert('Could not register abilities .. '.$e);

            return false;
        }
    }

    /**
     *  Forget the cached abilities.
     */
    public function forgetCachedPermissions()
    {
        $this->cache->forget($this->cacheKey);
    }

    /**
     * Get the current abilities.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getPermissions()
    {
        return $this->cache->rememberForever($this->cacheKey, function () {
            return AuzoAbility::with('permissions.policies', 'permissions.role.users')
                ->get();
        });
    }
}
