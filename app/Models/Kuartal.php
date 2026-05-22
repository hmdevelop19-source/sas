<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kuartal extends Model
{
    protected $fillable = ['name', 'start_date', 'end_date', 'is_active'];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function warnings()
    {
        return $this->hasMany(StudentWarning::class);
    }
}
