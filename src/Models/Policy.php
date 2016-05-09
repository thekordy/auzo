<?php

namespace Kordy\Auzo\Models;

use Illuminate\Database\Eloquent\Model;
use Kordy\Auzo\Traits\PolicyTrait;

/**
 * Kordy\Auzo\Models\Policy
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Kordy\Auzo\Models\Permission[] $permissions
 * @method static \Illuminate\Database\Query\Builder|\Kordy\Auzo\Models\Condition findByName($label)
 * @mixin \Eloquent
 */
class Policy extends Model
{
    use PolicyTrait;
    
    protected $fillable = ['name', 'method'];
    protected $table = 'policies';
    public $timestamps = false;
}