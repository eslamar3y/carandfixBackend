<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CarController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $cars = Car::with(['carType', 'carSubType', 'engineType', 'report', 'images'])
            ->where('user_id', $request->user()->id)
            ->where('status', 'approved')
            ->get();

        return response()->json([
            'error' => false,
            'message' => 'success',
            'data' => $cars->map(fn($car) => $this->formatCar($car)),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'vinNumber' => 'required|string|max:255|unique:cars,vin_number',
                'carTypeId' => 'required|exists:car_types,id',
                'carSubTypeId' => 'required|exists:car_sub_types,id',
                'engineTypeId' => 'required|exists:engine_types,id',
            ]);
        } catch (ValidationException $e) {
            $message = $e->errors()['vinNumber'][0] ?? __('validation.unique');
            return response()->json([
                'error' => true,
                'message' => $message,
            ]);
        }

        $carCount = Car::where('user_id', $request->user()->id)->count();
        if ($carCount >= 5) {
            return response()->json([
                'error' => true,
                'message' => __('cars.max_limit'),
            ]);
        }

        $car = Car::create([
            'user_id' => $request->user()->id,
            'vin_number' => $validated['vinNumber'],
            'car_type_id' => $validated['carTypeId'],
            'car_sub_type_id' => $validated['carSubTypeId'],
            'engine_type_id' => $validated['engineTypeId'],
            'status' => 'pending',
        ]);

        return response()->json([
            'error' => false,
            'message' => __('cars.added_for_approval'),
        ]);
    }

    public function show(Request $request, Car $car): JsonResponse
    {
        if ($car->user_id !== $request->user()->id) {
            return response()->json(['error' => true, 'message' => 'Forbidden'], 403);
        }

        $car->load(['carType', 'carSubType', 'engineType', 'report', 'images']);

        return response()->json([
            'error' => false,
            'message' => 'success',
            'data' => $this->formatCar($car),
        ]);
    }

    private function formatCar(Car $car): array
    {
        $report = $car->report;

        return [
            'id' => $car->id,
            'carName' => ($car->carType?->name ?? '') . ' ' . ($car->carSubType?->name ?? ''),
            'vinNumber' => $car->vin_number ?? '',
            'color' => $car->color && preg_match('/^#?[0-9A-Fa-f]+$/', $car->color) ? $car->color : '#000000',
            'lastOilChangeDate' => $car->last_oil_change_date ?? '',
            'registrationNumber' => $car->registration_number ?? '',
            'yearOfProduction' => $car->year_of_production ?? '',
            'enginePower' => $car->engine_power ?? '',
            'carTypeId' => $car->car_type_id,
            'carSubTypeId' => $car->car_sub_type_id,
            'engineTypeId' => $car->engine_type_id,
            'typeName' => $car->carType?->name ?? '',
            'typeImage' => $car->carType?->image ? request()->getSchemeAndHttpHost() . '/storage/' . $car->carType?->image : 'https://ui-avatars.com/api/?name=Car&background=random&size=200',
            'subTypename' => $car->carSubType?->name ?? '',
            'engineTypeName' => $car->engineType?->name ?? '',
            'status' => $car->status ?? '',
            'Report' => $report ? [
                'ReportDetails' => [
                    'serial' => $report->serial ?? '',
                    'FinalDecision' => match ($report->final_decision) {
                        'excellent' => '95',
                        'good' => '75',
                        'fair' => '65',
                        'poor' => '55',
                        default => '50',
                    },
                    'CurrentMileage' => $report->current_mileage ?? '',
                    'Date' => $report->report_date ?? '',
                    'CarOptions' => $report->car_options ?? '',
                    'ChassisAndFrameConditionPercent' => $report->chassis_percent ?? '',
                    'ExteriorPercent' => $report->exterior_percent ?? '',
                    'RoadTestPercent' => $report->road_test_percent ?? '',
                    'PowerTrainPercent' => $report->power_train_percent ?? '',
                    'ElectricalSystemPercent' => $report->electrical_percent ?? '',
                    'BrakingAndSafetySystemsPercent' => $report->braking_percent ?? '',
                    'SuspensionPercent' => $report->suspension_percent ?? '',
                    'ACAndEngineCoolingPercent' => $report->ac_cooling_percent ?? '',
                ],
                'ChassisAndFrameCondition' => $this->flattenItems($report->chassis_frame, ['FrontFrame','FrontFrameNotes','FrontFrameImage','RearFrame','RearFrameNotes','RearFrameImage','LowerFrame','LowerFrameNotes','LowerFrameImage','UpperFrame','UpperFrameNotes','UpperFrameImage','RightSideFrame','RightSideFrameNotes','RightSideFrameImage','LeftSideFrame','LeftSideFrameNotes','LeftSideFrameImage','RustExistence','RustExistenceNotes','RustExistenceImage']),
                'Exterior' => $this->flattenItems($report->exterior, ['SpareTire','SpareTireNotes','SpareTireImage','TireJackAndTools','TireJackAndToolsNotes','TireJackAndToolsImage','Wrench','WrenchNotes','WrenchImage','ExteriorImage','FrontBumper','FrontBumperNotes','FrontBumperImage','Hood','HoodNotes','HoodImage','FrontRightFender','FrontRightFenderNotes','FrontRightFenderImage','FrontRightDoor','FrontRightDoorNotes','FrontRightDoorImage','RearRightDoor','RearRightDoorNotes','RearRightDoorImage','RearRightFender','RearRightFenderNotes','RearRightFenderImage','RearBumper','RearBumperNotes','RearBumperImage','RearLeftFender','RearLeftFenderNotes','RearLeftFenderImage','RearLeftDoor','RearLeftDoorNotes','RearLeftDoorImage','FrontLeftDoor','FrontLeftDoorNotes','FrontLeftDoorImage','FrontLeftFender','FrontLeftFenderNotes','FrontLeftFenderImage','Roof','RoofNotes','RoofImage','Sunroof','SunroofNotes','SunroofImage','TailGate','TailGateNotes','TailGateImage','FrontWindshield','FrontWindshieldNotes','FrontWindshieldImage','RearWindshield','RearWindshieldNotes','RearWindshieldImage','InteriorCondition','InteriorConditionNotes','InteriorConditionImage','RightMirror','RightMirrorNotes','RightMirrorImage','LeftMirror','LeftMirrorNotes','LeftMirrorImage','WindshieldRubber','WindshieldRubberNotes','WindshieldRubberImage','DoorRubber','DoorRubberNotes','DoorRubberImage']),
                'RoadTest' => $this->flattenItems($report->road_test, ['SteeringWheelCentered','SteeringWheelCenteredNotes','SteeringWheelCenteredImage','VehicleDrivesStraightOnLevelSurfaces','VehicleDrivesStraightOnLevelSurfacesNotes','VehicleDrivesStraightOnLevelSurfacesImage','SteeringHasNormalFeelDuringOperation','SteeringHasNormalFeelDuringOperationNotes','SteeringHasNormalFeelDuringOperationImage','SteeringSystemComponents','SteeringSystemComponentsNotes','SteeringSystemComponentsImage','SuspensionComponents','SuspensionComponentsNotes','SuspensionComponentsImage','NoAbnormalNoiseOrVibrationFromSuspension','NoAbnormalNoiseOrVibrationFromSuspensionNotes','NoAbnormalNoiseOrVibrationFromSuspensionImage','NoAbnormalSqueaksAndRattlesWhileDriving','NoAbnormalSqueaksAndRattlesWhileDrivingNotes','NoAbnormalSqueaksAndRattlesWhileDrivingImage','EnginePerformanceAtOperatingTemperature','EnginePerformanceAtOperatingTemperatureNotes','EnginePerformanceAtOperatingTemperatureImage','TransmissionAndClutchPerformance','TransmissionAndClutchPerformanceNotes','TransmissionAndClutchPerformanceImage','AutomaticTransmissionOperation','AutomaticTransmissionOperationNotes','AutomaticTransmissionOperationImage','NoAbnormalTireRoadNoise','NoAbnormalTireRoadNoiseNotes','NoAbnormalTireRoadNoiseImage','BrakeSystemPerformance','BrakeSystemPerformanceNotes','BrakeSystemPerformanceImage','NoAbnormalBrakeVibrationsNoises','NoAbnormalBrakeVibrationsNoisesNotes','NoAbnormalBrakeVibrationsNoisesImage','CruiseControlOperatingAsDesigned','CruiseControlOperatingAsDesignedNotes','CruiseControlOperatingAsDesignedImage','TheOdometerIsWorkingCorrectly','TheOdometerIsWorkingCorrectlyNotes','TheOdometerIsWorkingCorrectlyImage']),
                'PowerTrain' => $this->flattenItems($report->power_train, ['ExhaustEmissionTest','ExhaustEmissionTestNotes','ExhaustEmissionTestImage','EnginePerformanceAndEfficiency','EnginePerformanceAndEfficiencyNotes','EnginePerformanceAndEfficiencyImage','TransmissionShiftsQuality','TransmissionShiftsQualityNotes','TransmissionShiftsQualityImage','FluidLeaks','FluidLeaksNotes','FluidLeaksImage','FAndRDifferentialNoiseCheck','FAndRDifferentialNoiseCheckNotes','FAndRDifferentialNoiseCheckImage','EngineFaults','EngineFaultsNotes','EngineFaultsImage','EngineDriveBelts','EngineDriveBeltsNotes','EngineDriveBeltsImage','TransmissionDTC','TransmissionDTCNotes','TransmissionDTCImage']),
                'ElectricalSystem' => $this->flattenItems($report->electrical_system, ['FrontRightLight','FrontRightLightNotes','FrontRightLightImage','FrontLeftLight','FrontLeftLightNotes','FrontLeftLightImage','RearRightLight','RearRightLightNotes','RearRightLightImage','RearLeftLight','RearLeftLightNotes','RearLeftLightImage','ES12VoltBattery','ES12VoltBatteryNotes','ES12VoltBatteryImage','ES12VoltBatteryCharge','ES12VoltBatteryChargeNotes','ES12VoltBatteryChargeImage','SunRoofPanorama','SunRoofPanoramaNotes','SunRoofPanoramaImage','PowerSeats','PowerSeatsNotes','PowerSeatsImage','RearWindowDefrost','RearWindowDefrostNotes','RearWindowDefrostImage','RadioScreen','RadioScreenNotes','RadioScreenImage','Speakers','SpeakersNotes','SpeakersImage','Horn','HornNotes','HornImage','InternalLightingSystem','InternalLightingSystemNotes','InternalLightingSystemImage','RemoteKeyControl','RemoteKeyControlNotes','RemoteKeyControlImage','RightMirrorES','RightMirrorESNotes','RightMirrorESImage','LeftMirrorES','LeftMirrorESNotes','LeftMirrorESImage','FrontWipers','FrontWipersNotes','FrontWipersImage']),
                'BrakingAndSafetySystems' => $this->flattenItems($report->braking_safety, ['SRSComponent','SRSComponentNotes','SRSComponentImage','FrontTreadDepth','FrontTreadDepthNotes','FrontTreadDepthImage','RearTreadDepth','RearTreadDepthNotes','RearTreadDepthImage','AgeLessThan4Years','AgeLessThan4YearsNotes','AgeLessThan4YearsImage','WheelNuts','WheelNutsNotes','WheelNutsImage','ABSandSkidSystems','ABSandSkidSystemsNotes','ABSandSkidSystemsImage','FrontRight','FrontRightNotes','FrontRightImage','FrontLeft','FrontLeftNotes','FrontLeftImage','RearRight','RearRightNotes','RearRightImage','RearLeft','RearLeftNotes','RearLeftImage','FrontRightSeatBelt','FrontRightSeatBeltNotes','FrontRightSeatBeltImage','FrontLeftSeatBelt','FrontLeftSeatBeltNotes','FrontLeftSeatBeltImage','RearRightSeatBelt','RearRightSeatBeltNotes','RearRightSeatBeltImage','RearLeftSeatBelt','RearLeftSeatBeltNotes','RearLeftSeatBeltImage']),
                'Suspension' => $this->flattenItems($report->suspension, ['FrontSuspensionDamping','FrontSuspensionDampingNotes','FrontSuspensionDampingImage','RearSuspensionDamping','RearSuspensionDampingNotes','RearSuspensionDampingImage','SteeringAssembly','SteeringAssemblyNotes','SteeringAssemblyImage','EngineTransmissionMounts','EngineTransmissionMountsNotes','EngineTransmissionMountsImage','FrontSubFrame','FrontSubFrameNotes','FrontSubFrameImage','RearSubFrame','RearSubFrameNotes','RearSubFrameImage','FrontRearAxels','FrontRearAxelsNotes','FrontRearAxelsImage','FrontSideSlip','FrontSideSlipNotes','FrontSideSlipImage','RearSideSlip','RearSideSlipNotes','RearSideSlipImage','FrontHubBearing','FrontHubBearingNotes','FrontHubBearingImage','RearHubBearing','RearHubBearingNotes','RearHubBearingImage','SteeringColumn','SteeringColumnNotes','SteeringColumnImage']),
                'ACAndEngineCooling' => $this->flattenItems($report->ac_cooling, ['EngineCoolingLeakTest','EngineCoolingLeakTestNotes','EngineCoolingLeakTestImage','Cooling','CoolingNotes','CoolingImage','Heating','HeatingNotes','HeatingImage','Directions','DirectionsNotes','DirectionsImage','ACSystemLeakTest','ACSystemLeakTestNotes','ACSystemLeakTestImage','EngineCoolingSystemCheck','EngineCoolingSystemCheckNotes','EngineCoolingSystemCheckImage','RadiatorFanOperationalTestCheck','RadiatorFanOperationalTestCheckNotes','RadiatorFanOperationalTestCheckImage']),
                'AllNotes' => $this->flattenItems(null, ['FrontFrameNotes','RearFrameNotes','LowerFrameNotes','UpperFrameNotes','RightSideFrameNotes','LeftSideFrameNotes','RustExistenceNotes','FrontBumperNotes','HoodNotes','FrontRightFenderNotes','FrontRightDoorNotes','RearRightDoorNotes','RearRightFenderNotes','RearBumperNotes','RearLeftFenderNotes','RearLeftDoorNotes','FrontLeftDoorNotes','FrontLeftFenderNotes','RoofNotes','SunroofNotes','TailGateNotes','FrontWindshieldNotes','RearWindshieldNotes','InteriorConditionNotes','RightMirrorNotes','LeftMirrorNotes','WindshieldRubberNotes','DoorRubberNotes','SpareTireNotes','TireJackAndToolsNotes','WrenchNotes','SteeringWheelCenteredNotes','VehicleDrivesStraightOnLevelSurfacesNotes','SteeringHasNormalFeelDuringOperationNotes','SteeringSystemComponentsNotes','SuspensionComponentsNotes','NoAbnormalNoiseOrVibrationFromSuspensionNotes','NoAbnormalSqueaksAndRattlesWhileDrivingNotes','EnginePerformanceAtOperatingTemperatureNotes','TransmissionAndClutchPerformanceNotes','AutomaticTransmissionOperationNotes','NoAbnormalTireRoadNoiseNotes','BrakeSystemPerformanceNotes','NoAbnormalBrakeVibrationsNoisesNotes','CruiseControlOperatingAsDesignedNotes','TheOdometerIsWorkingCorrectlyNotes','ExhaustEmissionTestNotes','EnginePerformanceAndEfficiencyNotes','TransmissionShiftsQualityNotes','FluidLeaksNotes','FAndRDifferentialNoiseCheckNotes','EngineFaultsNotes','EngineDriveBeltsNotes','TransmissionDTCNotes','FrontRightLightNotes','FrontLeftLightNotes','RearRightLightNotes','RearLeftLightNotes','ES12VoltBatteryNotes','ES12VoltBatteryChargeNotes','SunRoofPanoramaNotes','PowerSeatsNotes','RearWindowDefrostNotes','RadioScreenNotes','SpeakersNotes','HornNotes','InternalLightingSystemNotes','RemoteKeyControlNotes','RightMirrorESNotes','LeftMirrorESNotes','FrontWipersNotes','SRSComponentNotes','FrontTreadDepthNotes','RearTreadDepthNotes','AgeLessThan4YearsNotes','WheelNutsNotes','ABSandSkidSystemsNotes','FrontRightNotes','FrontLeftNotes','RearRightNotes','RearLeftNotes','FrontRightSeatBeltNotes','FrontLeftSeatBeltNotes','RearRightSeatBeltNotes','RearLeftSeatBeltNotes','FrontSuspensionDampingNotes','RearSuspensionDampingNotes','SteeringAssemblyNotes','EngineTransmissionMountsNotes','FrontSubFrameNotes','RearSubFrameNotes','FrontRearAxelsNotes','FrontSideSlipNotes','RearSideSlipNotes','FrontHubBearingNotes','RearHubBearingNotes','SteeringColumnNotes','EngineCoolingLeakTestNotes','CoolingNotes','HeatingNotes','DirectionsNotes','ACSystemLeakTestNotes','EngineCoolingSystemCheckNotes','RadiatorFanOperationalTestCheckNotes']),
                'InspectionSystems' => [
                    'ExteriorInspectionSystems' => $this->countStatuses($report->exterior),
                    'ChassisAndFrameConditionInspectionSystems' => $this->countStatuses($report->chassis_frame),
                    'RoadTestInspectionSystems' => $this->countStatuses($report->road_test),
                    'PowerTrainInspectionSystems' => $this->countStatuses($report->power_train),
                    'ElectricalSystemInspectionSystems' => $this->countStatuses($report->electrical_system),
                    'BrakingAndSafetySystemsInspectionSystems' => $this->countStatuses($report->braking_safety),
                    'SuspensionInspectionSystems' => $this->countStatuses($report->suspension),
                    'ACAndEngineCoolingInspectionSystems' => $this->countStatuses($report->ac_cooling),
                ],
                'CarImages' => $car->images->where('type', 'car')->pluck('image')->filter()->values(),
                'Gallery' => $car->images->where('type', 'gallery')->pluck('image')->filter()->values(),
            ] : null,
        ];
    }

    private function countStatuses(?array $items): array
    {
        $good = 0;
        $average = 0;
        $bad = 0;

        foreach ($items ?? [] as $item) {
            $status = $item['status'] ?? '';
            if (in_array($status, ['good', 'excellent'])) {
                $good++;
            } elseif ($status === 'fair') {
                $average++;
            } else {
                $bad++;
            }
        }

        return [
            'Good' => $good,
            'Avreage' => $average,
            'Bad' => $bad,
        ];
    }

    private function flattenItems(?array $items, array $fields): array
    {
        $result = [];
        for ($i = 0; $i < count($fields); $i += 3) {
            $baseKey = $fields[$i];
            $notesKey = $fields[$i + 1] ?? $baseKey . 'Notes';
            $imageKey = $fields[$i + 2] ?? $baseKey . 'Image';
            $itemIndex = intdiv($i, 3);

            $item = $items[$itemIndex] ?? null;
            if ($item && isset($item['status'])) {
                $status = $item['status'];
                $result[$baseKey] = match ($status) {
                    'good', 'excellent' => 'Good Condition',
                    'fair' => 'Avreage Condition',
                    'poor', 'not_checked' => 'Bad Condition',
                    default => 'Good Condition',
                };
                $result[$notesKey] = $item['notes'] ?? '';
            } else {
                $result[$baseKey] = 'Good Condition';
                $result[$notesKey] = '';
            }
            $result[$imageKey] = '-';
        }
        return $result;
    }
}
