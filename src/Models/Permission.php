<?php

namespace Kordy\Auzo\Models;

use Illuminate\Database\Eloquent\Model;
use Kordy\Auzo\Traits\PermissionTrait;

class Permission extends Model
{
    use PermissionTrait;
    
    protected $fillable = ['ability_id', 'role_id'];
    protected $table = 'permissions';
    public $timestamps = false;
}