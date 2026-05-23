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

        // Menjalankan Seeder Master Wilayah (Download & Parse ~83rb data)
        $this->call(WilayahSeeder::class);

        // Mengambil salah satu data wilayah yang baru disinkronisasi (misal: Gegerkalong, Kota Bandung)
        $vill = \App\Models\Village::where('name', 'Gegerkalong')->first() 
                ?? \App\Models\Village::first();

        // Pendidikan & Pekerjaan
        $eduS1 = \App\Models\Education::create(['name' => 'S1 / D4']);
        $eduSMA = \App\Models\Education::create(['name' => 'SLTA / SEDERAJAT']);
        $occPNS = \App\Models\Occupation::create(['name' => 'PEGAWAI NEGERI SIPIL']);
        $occWiraswasta = \App\Models\Occupation::create(['name' => 'WIRASWASTA']);

        // Teacher
        $teacher1 = \App\Models\Teacher::create([
            'nip' => '198001012005011001', 'name' => 'Ahmad Suhendra, S.Pd', 'nik' => '3273010101800001',
            'gender' => 'L', 'place_of_birth' => 'Bandung', 'date_of_birth' => '1980-01-01',
            'religion' => 'Islam', 'blood_type' => 'O', 'village_id' => $vill->id, 'education_id' => $eduS1->id, 'occupation_id' => $occPNS->id
        ]);

        // Department
        $dept1 = \App\Models\Department::create(['name' => 'Rekayasa Perangkat Lunak', 'code' => 'RPL']);
        $dept2 = \App\Models\Department::create(['name' => 'Teknik Komputer dan Jaringan', 'code' => 'TKJ']);

        // Class
        $class1 = \App\Models\SchoolClass::create(['department_id' => $dept1->id, 'teacher_id' => $teacher1->id, 'name' => '10 RPL 1', 'grade' => 10]);

        // Students
        // (Kosong, menunggu input real dari form)

        // Guardians
        // (Kosong, menunggu input real dari form)
    }
}
