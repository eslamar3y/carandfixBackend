<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BatteryVoltage;
use App\Models\CarType;
use App\Models\EngineType;
use Illuminate\Http\JsonResponse;

class LookupController extends Controller
{
    public function carTypes(): JsonResponse
    {
        $carTypes = CarType::with('carSubTypes')->orderBy('name')->get();

        return response()->json([
            'error' => false,
            'message' => 'success',
            'data' => $carTypes->map(fn($ct) => [
                'id' => $ct->id,
                'name' => $ct->name,
                'image' => $ct->image,
                'carSubType' => $ct->carSubTypes->map(fn($cst) => [
                    'id' => $cst->id,
                    'name' => $cst->name,
                    'carTypeId' => $cst->car_type_id,
                ]),
            ]),
        ]);
    }

    public function engineTypes(): JsonResponse
    {
        $engineTypes = EngineType::all()->makeHidden('name_ar');

        return response()->json([
            'error' => false,
            'message' => 'success',
            'data' => $engineTypes,
        ]);
    }

    public function batteryVoltageTypes(): JsonResponse
    {
        $batteryVoltages = BatteryVoltage::all()->makeHidden('name_ar');

        return response()->json([
            'error' => false,
            'message' => 'success',
            'data' => $batteryVoltages,
        ]);
    }
}
