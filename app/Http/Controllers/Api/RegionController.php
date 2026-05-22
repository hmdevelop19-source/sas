<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;

class RegionController extends Controller
{
    public function provinces()
    {
        return response()->json(Province::select('id', 'code', 'name')->orderBy('name')->get());
    }

    public function regencies($provinceCode)
    {
        $province = Province::where('code', $provinceCode)->first();
        if (!$province) return response()->json([]);
        
        return response()->json(Regency::where('province_id', $province->id)
                                        ->select('id', 'code', 'name')
                                        ->orderBy('name')
                                        ->get());
    }

    public function districts($regencyCode)
    {
        $regency = Regency::where('code', $regencyCode)->first();
        if (!$regency) return response()->json([]);
        
        return response()->json(District::where('regency_id', $regency->id)
                                        ->select('id', 'code', 'name')
                                        ->orderBy('name')
                                        ->get());
    }

    public function villages($districtCode)
    {
        $district = District::where('code', $districtCode)->first();
        if (!$district) return response()->json([]);
        
        return response()->json(Village::where('district_id', $district->id)
                                       ->select('id', 'code', 'name')
                                       ->orderBy('name')
                                       ->get());
    }
}
