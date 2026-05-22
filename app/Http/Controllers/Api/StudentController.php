<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentWarning;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Tampilkan data siswa beserta total Alpha dan SP berjalan.
     */
    public function index(Request $request)
    {
        $kuartalId = $request->query('kuartal_id');

        $students = Student::withCount(['attendances as alpha_count' => function ($query) use ($kuartalId) {
            $query->where('status', 4);
            if ($kuartalId) {
                $query->where('kuartal_id', $kuartalId);
            }
        }])->with(['warnings' => function ($query) use ($kuartalId) {
            if ($kuartalId) {
                $query->where('kuartal_id', $kuartalId);
            }
            $query->latest('sp_level');
        }])->get();

        return response()->json([
            'data' => $students
        ]);
    }

    /**
     * Rekapitulasi daftar siswa yang terkena SP.
     */
    public function warnings(Request $request)
    {
        $warnings = StudentWarning::with(['student.schoolClass.department'])
            ->orderBy('issued_at', 'desc')
            ->get();

        return response()->json([
            'data' => $warnings
        ]);
    }
}
