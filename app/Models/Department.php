<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name', 'code'];

    public function classes()
    {
        return $this->hasMany(SchoolClass::class);
    }
}
