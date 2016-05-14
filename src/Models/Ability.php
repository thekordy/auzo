<?php

namespace Kordy\Auzo\Models;

use Illuminate\Database\Eloquent\Model;
use Kordy\Auzo\Traits\AbilityTrait;

class Ability extends Model
{
    use AbilityTrait;

    protected $fillable = ['name', 'label', 'tag'];
    protected $table = 'abilities';
    public $timestamps = false;
}
