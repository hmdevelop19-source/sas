<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['school_class_id', 'name', 'nis', 'gender'];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function warnings()
    {
        return $this->hasMany(StudentWarning::class);
    }

    public function guardian()
    {
        return $this->hasOne(Guardian::class);
    }
}
