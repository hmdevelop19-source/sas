<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guardian;
use App\Models\Village;
use App\Models\District;
use App\Models\Regency;

class GuardianController extends Controller
{
    public function index()
    {
        $guardians = Guardian::with(['education', 'occupation', 'students'])->get();
        return response()->json(['data' => $guardians]);
    }

    public function check(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|size:16'
        ]);

        $guardian = Guardian::where('nik', $request->nik)->first();

        if (!$guardian) {
            return response()->json(['exists' => false, 'data' => null]);
        }

        // Resolusi kode wilayah dari village_id
        $village = Village::find($guardian->village_id);
        $district = $village ? District::find($village->district_id) : null;
        $regency = $district ? Regency::find($district->regency_id) : null;
        $province = $regency ? $regency->province_id : null;

        return response()->json([
            'exists' => true,
            'data' => [
                'nkk' => $guardian->nkk,
                'nik' => $guardian->nik,
                'name' => $guardian->name,
                'date_of_birth' => $guardian->date_of_birth,
                'gender' => $guardian->gender,
                'phone' => $guardian->phone,
                'address' => $guardian->address,
                'education_id' => $guardian->education_id,
                'occupation_id' => $guardian->occupation_id,
                'region' => [
                    'province_code' => $regency ? substr($regency->code, 0, 2) : null,
                    'regency_code' => $regency ? $regency->code : null,
                    'district_code' => $district ? $district->code : null,
                    'village_code' => $village ? $village->code : null,
                ]
            ]
        ]);
    }
}
