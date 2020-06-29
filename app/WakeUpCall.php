<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WakeUpCall extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ext', 'datetime', 'tries', 'waittime', 'retrytime', 'supervisor'];
}
