<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $url = 'https://raw.githubusercontent.com/cahyadsn/wilayah/master/db/wilayah.sql';
        $path = storage_path('app/wilayah.sql');
        
        if (!file_exists($path)) {
            $this->command->info("Mengunduh data wilayah.sql (~9MB) dari GitHub...");
            $content = file_get_contents($url);
            if ($content) {
                file_put_contents($path, $content);
            } else {
                $this->command->error("Gagal mengunduh file wilayah.sql. Cek koneksi internet.");
                return;
            }
        }

        $this->command->info("Membaca dan memproses sinkronisasi wilayah...");
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Village::truncate();
        District::truncate();
        Regency::truncate();
        Province::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $handle = fopen($path, 'r');
        if (!$handle) {
            $this->command->error("Gagal membaca file lokal wilayah.sql");
            return;
        }
        
        $provincesMap = [];
        $regenciesMap = [];
        $districtsMap = [];
        $villagesBatch = [];
        
        $this->command->getOutput()->progressStart(83800); // Total estimasi data Indonesia
        
        $now = now();
        
        while (($line = fgets($handle)) !== false) {
            $line = trim($line);
            // Regex mencari pola: ('11.01.01.2001','Nama Wilayah')
            if (preg_match('/^\(\'([\d\.]+)\',\'(.*)\'\)/', $line, $matches)) {
                $code = $matches[1];
                $name = str_replace("''", "'", $matches[2]); // unescape SQL single quotes
                $length = strlen($code);
                
                if ($length === 2) {
                    $p = Province::create(['code' => $code, 'name' => $name]);
                    $provincesMap[$code] = $p->id;
                } elseif ($length === 5) {
                    $provCode = substr($code, 0, 2);
                    if (isset($provincesMap[$provCode])) {
                        $r = Regency::create(['province_id' => $provincesMap[$provCode], 'code' => $code, 'name' => $name]);
                        $regenciesMap[$code] = $r->id;
                    }
                } elseif ($length === 8) {
                    $regCode = substr($code, 0, 5);
                    if (isset($regenciesMap[$regCode])) {
                        $d = District::create(['regency_id' => $regenciesMap[$regCode], 'code' => $code, 'name' => $name]);
                        $districtsMap[$code] = $d->id;
                    }
                } elseif ($length === 13) {
                    $distCode = substr($code, 0, 8);
                    if (isset($districtsMap[$distCode])) {
                        $villagesBatch[] = [
                            'district_id' => $districtsMap[$distCode],
                            'code' => $code,
                            'name' => $name,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                        
                        // Insert per 1000 data agar tidak OOM
                        if (count($villagesBatch) >= 1000) {
                            Village::insert($villagesBatch);
                            $villagesBatch = [];
                        }
                    }
                }
                
                $this->command->getOutput()->progressAdvance();
            }
        }
        
        // Insert sisa data
        if (!empty($villagesBatch)) {
            Village::insert($villagesBatch);
        }
        
        fclose($handle);
        $this->command->getOutput()->progressFinish();
        $this->command->info("Sinkronisasi 83.000+ Master Wilayah Selesai dengan Sukses! 🎉");
    }
}
