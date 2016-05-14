<?php

namespace Kordy\Auzo\Models;

use Illuminate\Database\Eloquent\Model;
use Kordy\Auzo\Traits\RoleTrait;

class Role extends Model
{
    use RoleTrait;

    protected $fillable = ['name', 'description'];
    protected $table = 'roles';
    public $timestamps = false;
}
