<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpSetting extends Model
{
    protected $fillable = ['sp_level', 'max_alpha', 'description'];
}
