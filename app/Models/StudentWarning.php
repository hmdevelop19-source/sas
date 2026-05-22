<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentWarning extends Model
{
    protected $fillable = ['student_id', 'sp_level', 'kuartal_id', 'issued_at', 'is_signed'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function kuartal()
    {
        return $this->belongsTo(Kuartal::class);
    }
}
