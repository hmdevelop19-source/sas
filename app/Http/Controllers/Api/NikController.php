<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;

class NikController extends Controller
{
    /**
     * Parse NIK dan kembalikan data demografi dasar.
     */
    public function parse(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|size:16'
        ]);

        $nik = $request->nik;

        // Ekstraksi kode wilayah
        $provCode = substr($nik, 0, 2);
        $regCode = substr($nik, 0, 4);
        $distCode = substr($nik, 0, 6);

        // Ekstraksi tanggal lahir
        $dd = (int) substr($nik, 6, 2);
        $mm = (int) substr($nik, 8, 2);
        $yy = (int) substr($nik, 10, 2);

        // Penentuan jenis kelamin (Perempuan ditambahkan 40 pada tanggal lahir)
        $gender = 'L';
        if ($dd > 40) {
            $gender = 'P';
            $dd -= 40;
        }

        // Penentuan tahun lahir (asumsi: < 30 berarti 20xx, > 30 berarti 19xx)
        $year = $yy < 30 ? 2000 + $yy : 1900 + $yy;

        try {
            $dob = Carbon::createFromDate($year, $mm, $dd)->format('Y-m-d');
        } catch (\Exception $e) {
            $dob = null;
        }

        // Cari wilayah di database
        $province = Province::where('code', $provCode)->first();
        $regency = Regency::where('code', $regCode)->first();
        $district = District::where('code', $distCode)->first();

        return response()->json([
            'nik' => $nik,
            'gender' => $gender,
            'date_of_birth' => $dob,
            'region' => [
                'province' => $province ? $province->name : null,
                'regency' => $regency ? $regency->name : null,
                'district' => $district ? $district->name : null,
            ]
        ]);
    }
}
