<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // SpSettings
        \App\Models\SpSetting::create(['sp_level' => 1, 'max_alpha' => 3, 'description' => 'Surat Peringatan 1']);
        \App\Models\SpSetting::create(['sp_level' => 2, 'max_alpha' => 6, 'description' => 'Surat Peringatan 2']);
        \App\Models\SpSetting::create(['sp_level' => 3, 'max_alpha' => 9, 'description' => 'Surat Peringatan 3 / Panggilan Orang Tua']);

        // Kuartal
        $q1 = \App\Models\Kuartal::create(['name' => 'Q1 2026/2027', 'start_date' => '2026-07-01', 'end_date' => '2026-09-30', 'is_active' => true]);

        // Department
        $dept1 = \App\Models\Department::create(['name' => 'Rekayasa Perangkat Lunak', 'code' => 'RPL']);
        $dept2 = \App\Models\Department::create(['name' => 'Teknik Komputer dan Jaringan', 'code' => 'TKJ']);

        // Class
        $class1 = \App\Models\SchoolClass::create(['department_id' => $dept1->id, 'name' => '10 RPL 1', 'grade' => 10]);

        // Students
        \App\Models\Student::create(['school_class_id' => $class1->id, 'name' => 'Budi Santoso', 'nis' => '1001', 'gender' => 'L']);
        \App\Models\Student::create(['school_class_id' => $class1->id, 'name' => 'Siti Aminah', 'nis' => '1002', 'gender' => 'P']);
    }
}
