<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Governorate;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function subAreas(District $district)
    {
        return response()->json($district->subAreas);
    }

    public function districtsByGovernorate(Governorate $governorate)
    {
        return response()->json($governorate->districts);
    }
}
