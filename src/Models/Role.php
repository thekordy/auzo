<?php

namespace Kordy\Auzo\Models;

use Kordy\Auzo\Traits\RoleTrait;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use RoleTrait;
    
    protected $fillable = ['name', 'description'];
    protected $table = 'roles';
    public $timestamps = false;
}