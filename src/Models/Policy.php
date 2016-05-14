<?php

namespace Kordy\Auzo\Models;

use Illuminate\Database\Eloquent\Model;
use Kordy\Auzo\Traits\PolicyTrait;

class Policy extends Model
{
    use PolicyTrait;
    
    protected $fillable = ['name', 'method'];
    protected $table = 'policies';
    public $timestamps = false;
}