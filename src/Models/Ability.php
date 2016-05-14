<?php

namespace Kordy\Auzo\Models;

use Kordy\Auzo\Traits\AbilityTrait;
use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
    use AbilityTrait; 
    
    protected $fillable = ['name', 'label', 'tag'];
    protected $table = 'abilities';
    public $timestamps = false;
}