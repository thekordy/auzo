<?php

namespace Kordy\Auzo\Models;

use Illuminate\Database\Eloquent\Model;
use Kordy\Auzo\Traits\PermissionTrait;

/**
 * Kordy\Auzo\Models\Permission
 *
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $permissionable
 * @property-read \Kordy\Auzo\Models\Ability $ability
 * @property-read \Illuminate\Database\Eloquent\Collection|\Kordy\Auzo\Models\Condition[] $conditions
 * @method static \Illuminate\Database\Query\Builder|\Kordy\Auzo\Models\Permission forAbility($abilities)
 * @mixin \Eloquent
 */
class Permission extends Model
{
    use PermissionTrait;
    
    protected $fillable = ['ability_id', 'role_id'];
    protected $table = 'permissions';
    public $timestamps = false;
}