<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Education;
use App\Models\Occupation;

class MasterDataController extends Controller
{
    public function educations()
    {
        return response()->json(Education::select('id', 'name')->orderBy('id')->get());
    }

    public function occupations()
    {
        return response()->json(Occupation::select('id', 'name')->orderBy('name')->get());
    }
}
