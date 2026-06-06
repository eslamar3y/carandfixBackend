<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminCarController extends Controller
{
    public function pending(): JsonResponse
    {
        $cars = Car::with(['user', 'carType', 'carSubType', 'engineType'])
            ->where('status', 'pending')
            ->get();

        return response()->json([
            'error' => false,
            'message' => 'success',
            'data' => $cars,
        ]);
    }

    public function approve(Car $car): JsonResponse
    {
        $car->update(['status' => 'approved']);

        $car->user->update(['is_active' => true]);

        return response()->json([
            'error' => false,
            'message' => __('cars.approved'),
        ]);
    }

    public function reject(Request $request, Car $car): JsonResponse
    {
        $car->update(['status' => 'rejected']);

        return response()->json([
            'error' => false,
            'message' => __('cars.rejected'),
        ]);
    }
}
