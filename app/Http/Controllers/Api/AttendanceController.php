<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Input absensi harian untuk siswa/kelas.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'school_class_id' => 'required|exists:school_classes,id',
            'kuartal_id' => 'required|exists:kuartals,id',
            'status' => 'required|integer|in:1,2,3,4', // 1: Hadir, 2: Izin, 3: Sakit, 4: Alpha
            'notes' => 'nullable|string',
        ]);

        $attendance = Attendance::create($validated);

        return response()->json([
            'message' => 'Data absensi berhasil disimpan.',
            'data' => $attendance
        ], 201);
    }
}
