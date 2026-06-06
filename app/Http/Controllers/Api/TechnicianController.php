<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Order;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TechnicianController extends Controller
{
    public function assignedOrders(): JsonResponse
    {
        $orders = Order::with('user', 'car')
            ->where('technician_id', request()->user()->id)
            ->orWhere('status', 'accepted')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'error' => false,
            'message' => 'success',
            'data' => $orders,
        ]);
    }

    public function submitReport(Request $request, Car $car): JsonResponse
    {
        $validated = $request->validate([
            'serial' => 'nullable|integer',
            'FinalDecision' => 'nullable|string',
            'CurrentMileage' => 'nullable|string',
            'Date' => 'nullable|string',
            'CarOptions' => 'nullable|string',
            'ChassisAndFrameConditionPercent' => 'nullable|integer|min:0|max:100',
            'ExteriorPercent' => 'nullable|integer|min:0|max:100',
            'RoadTestPercent' => 'nullable|integer|min:0|max:100',
            'PowerTrainPercent' => 'nullable|integer|min:0|max:100',
            'ElectricalSystemPercent' => 'nullable|integer|min:0|max:100',
            'BrakingAndSafetySystemsPercent' => 'nullable|integer|min:0|max:100',
            'SuspensionPercent' => 'nullable|integer|min:0|max:100',
            'ACAndEngineCoolingPercent' => 'nullable|integer|min:0|max:100',
            'Exterior' => 'nullable|array',
            'ChassisAndFrameCondition' => 'nullable|array',
            'RoadTest' => 'nullable|array',
            'PowerTrain' => 'nullable|array',
            'ElectricalSystem' => 'nullable|array',
            'BrakingAndSafetySystems' => 'nullable|array',
            'Suspension' => 'nullable|array',
            'ACAndEngineCooling' => 'nullable|array',
            'AllNotes' => 'nullable|array',
            'InspectionSystems' => 'nullable|array',
            'NoteImages' => 'nullable|array',
        ]);

        $report = Report::updateOrCreate(
            ['car_id' => $car->id],
            [
                'technician_id' => $request->user()->id,
                'serial' => $validated['serial'] ?? null,
                'final_decision' => $validated['FinalDecision'] ?? null,
                'current_mileage' => $validated['CurrentMileage'] ?? null,
                'report_date' => $validated['Date'] ?? null,
                'car_options' => $validated['CarOptions'] ?? null,
                'chassis_percent' => $validated['ChassisAndFrameConditionPercent'] ?? null,
                'exterior_percent' => $validated['ExteriorPercent'] ?? null,
                'road_test_percent' => $validated['RoadTestPercent'] ?? null,
                'power_train_percent' => $validated['PowerTrainPercent'] ?? null,
                'electrical_percent' => $validated['ElectricalSystemPercent'] ?? null,
                'braking_percent' => $validated['BrakingAndSafetySystemsPercent'] ?? null,
                'suspension_percent' => $validated['SuspensionPercent'] ?? null,
                'ac_cooling_percent' => $validated['ACAndEngineCoolingPercent'] ?? null,
                'exterior' => $validated['Exterior'] ?? null,
                'chassis_frame' => $validated['ChassisAndFrameCondition'] ?? null,
                'road_test' => $validated['RoadTest'] ?? null,
                'power_train' => $validated['PowerTrain'] ?? null,
                'electrical_system' => $validated['ElectricalSystem'] ?? null,
                'braking_safety' => $validated['BrakingAndSafetySystems'] ?? null,
                'suspension' => $validated['Suspension'] ?? null,
                'ac_cooling' => $validated['ACAndEngineCooling'] ?? null,
                'all_notes' => $validated['AllNotes'] ?? null,
                'inspection_systems' => $validated['InspectionSystems'] ?? null,
                'note_images' => $validated['NoteImages'] ?? null,
            ]
        );

        return response()->json([
            'error' => false,
            'message' => 'Report submitted successfully.',
            'data' => $report,
        ]);
    }
}
