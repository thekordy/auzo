<?php

namespace Kordy\Auzo\Models;

use Kordy\Auzo\Traits\AbilityTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Kordy\Auzo\Models\Ability
 *
 * @property integer $id
 * @property string $name
 * @property string $label
 * @property-read \Illuminate\Database\Eloquent\Collection|\Kordy\Auzo\Models\Permission[] $permissions
 * @method static \Illuminate\Database\Query\Builder|\Kordy\Auzo\Models\Ability whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Kordy\Auzo\Models\Ability whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Kordy\Auzo\Models\Ability whereLabel($value)
 * @method static \Illuminate\Database\Query\Builder|\Kordy\Auzo\Models\Ability findByName($name)
 * @mixin \Eloquent
 */
class Ability extends Model
{
    use AbilityTrait; 
    
    protected $fillable = ['name', 'label'];
    protected $table = 'abilities';
    public $timestamps = false;
}