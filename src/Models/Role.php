<?php

namespace Kordy\Auzo\Models;

use Kordy\Auzo\Traits\RoleTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Kordy\Auzo\Models\Role
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property-read \Illuminate\Database\Eloquent\Collection|\Kordy\Auzo\Models\Permission[] $permissions
 * @method static \Illuminate\Database\Query\Builder|\Kordy\Auzo\Models\Role whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Kordy\Auzo\Models\Role whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Kordy\Auzo\Models\Role whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\Kordy\Auzo\Models\Role findByName($name)
 * @mixin \Eloquent
 */
class Role extends Model
{
    use RoleTrait;
    
    protected $fillable = ['name', 'description'];
    protected $table = 'roles';
    public $timestamps = false;
}