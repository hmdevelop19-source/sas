<?php

namespace App\Observers;

use App\Models\Attendance;
use App\Models\SpSetting;
use App\Models\StudentWarning;

class AttendanceObserver
{
    /**
     * Handle the Attendance "created" event.
     */
    public function created(Attendance $attendance): void
    {
        $this->checkAndIssueWarning($attendance);
    }

    /**
     * Handle the Attendance "updated" event.
     */
    public function updated(Attendance $attendance): void
    {
        // Only check if status was changed to 4
        if ($attendance->wasChanged('status') && $attendance->status == 4) {
            $this->checkAndIssueWarning($attendance);
        }
    }

    private function checkAndIssueWarning(Attendance $attendance): void
    {
        // 4 adalah kode untuk Alpha
        if ($attendance->status != 4) {
            return;
        }

        // Hitung total Alpha siswa di kuartal ini
        $totalAlpha = Attendance::where('student_id', $attendance->student_id)
            ->where('kuartal_id', $attendance->kuartal_id)
            ->where('status', 4)
            ->count();

        // Ambil semua setting SP, urutkan dari yang terbesar (misal: SP 3 = 9 alpha, SP 1 = 3 alpha)
        $spSettings = SpSetting::orderBy('max_alpha', 'desc')->get();

        foreach ($spSettings as $setting) {
            if ($totalAlpha >= $setting->max_alpha) {
                // Cek apakah SP level ini sudah pernah diterbitkan di kuartal ini
                $warningExists = StudentWarning::where('student_id', $attendance->student_id)
                    ->where('kuartal_id', $attendance->kuartal_id)
                    ->where('sp_level', $setting->sp_level)
                    ->exists();

                if (!$warningExists) {
                    // Terbitkan SP
                    StudentWarning::create([
                        'student_id' => $attendance->student_id,
                        'sp_level' => $setting->sp_level,
                        'kuartal_id' => $attendance->kuartal_id,
                        'issued_at' => now(),
                        'is_signed' => false,
                    ]);

                    // Jika sudah kena SP tertinggi yang sesuai, hentikan pengecekan
                    break;
                }
            }
        }
    }
}
