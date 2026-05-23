<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentWarning;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Simpan data siswa dan wali baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'guardian_nik' => 'required|string|size:16',
            'guardian_name' => 'required|string',
            'student_nik' => 'required|string|size:16',
            'student_name' => 'required|string',
            'student_village_code' => 'required|string',
            'student_gender' => 'required|in:L,P',
        ]);

        $village = \App\Models\Village::where('code', $request->student_village_code)->first();
        $schoolClass = \App\Models\SchoolClass::first(); // Default fallback
        $villageId = $village ? $village->id : null;

        $guardian = \App\Models\Guardian::firstOrCreate(
            ['nik' => $request->guardian_nik],
            [
                'nkk' => $request->guardian_nkk,
                'name' => $request->guardian_name,
                'phone' => $request->guardian_phone,
                'date_of_birth' => $request->guardian_dob,
                'gender' => $request->guardian_gender,
                'education_id' => $request->guardian_education_id ?: null,
                'occupation_id' => $request->guardian_occupation_id ?: null,
                'village_id' => $villageId,
            ]
        );

        $student = \App\Models\Student::create([
            'guardian_id' => $guardian->id,
            'school_class_id' => $schoolClass ? $schoolClass->id : 1,
            'village_id' => $villageId,
            'name' => $request->student_name,
            'nis' => 'NIS' . rand(1000, 9999) . date('y'), // Random NIS untuk simulasi
            'nik' => $request->student_nik,
            'gender' => $request->student_gender,
            'place_of_birth' => $request->student_pob,
            'date_of_birth' => $request->student_dob,
            'address' => $request->student_address,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Siswa berhasil didaftarkan.',
            'data' => $student
        ]);
    }

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
