<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $user = User::firstOrCreate(
            ['email' => 'demo@test.com'],
            [
                'name' => 'Demo User',
                'password' => 'password',
                'phone' => '01000000999',
                'role' => 'customer',
                'is_verified' => true,
                'is_active' => true,
                'email_verified_at' => $now,
            ]
        );

        $car = Car::firstOrCreate(
            ['vin_number' => 'SAMPLE12345678901'],
            [
                'user_id' => $user->id,
                'car_type_id' => 1,
                'car_sub_type_id' => 1,
                'engine_type_id' => 1,
                'status' => 'approved',
                'color' => 'White',
                'registration_number' => 'SMP123',
                'year_of_production' => '2021',
                'engine_power' => '180 HP',
            ]
        );

        $tech = User::where('role', 'technician')->first();
        if (!$tech) {
            $tech = User::create([
                'name' => 'Demo Technician',
                'email' => 'demotech@test.com',
                'password' => 'password',
                'phone' => '01000000998',
                'role' => 'technician',
                'is_verified' => true,
                'is_active' => true,
                'email_verified_at' => $now,
            ]);
        }

        Report::updateOrCreate(
            ['car_id' => $car->id],
            [
                'technician_id' => $tech->id,
                'serial' => 25001,
                'final_decision' => 'good',
                'current_mileage' => '45,320 km',
                'report_date' => '2026-05-25',
                'car_options' => 'Sunroof, Leather Seats, Navigation, Backup Camera',

                'exterior_percent' => 82,
                'chassis_percent' => 75,
                'road_test_percent' => 88,
                'power_train_percent' => 90,
                'electrical_percent' => 65,
                'braking_percent' => 78,
                'suspension_percent' => 70,
                'ac_cooling_percent' => 85,

                'exterior' => [
                    ['name' => 'Paint Condition', 'status' => 'good', 'notes' => 'Minor swirl marks on hood'],
                    ['name' => 'Body Panels', 'status' => 'good', 'notes' => 'No dents or rust'],
                    ['name' => 'Glass & Mirrors', 'status' => 'excellent', 'notes' => 'No cracks'],
                    ['name' => 'Lighting (Head/Tail)', 'status' => 'good', 'notes' => 'All functioning'],
                    ['name' => 'Wheels & Tires', 'status' => 'fair', 'notes' => 'Front tires at 40% tread, rear at 60%'],
                    ['name' => 'Door Alignment', 'status' => 'good', 'notes' => ''],
                ],

                'chassis_frame' => [
                    ['name' => 'Frame Integrity', 'status' => 'good', 'notes' => 'No signs of previous accident'],
                    ['name' => 'Underbody Rust', 'status' => 'fair', 'notes' => 'Surface rust on exhaust, structurally sound'],
                    ['name' => 'Subframe Mounts', 'status' => 'excellent', 'notes' => 'No play'],
                ],

                'road_test' => [
                    ['name' => 'Engine Start & Idle', 'status' => 'good', 'notes' => 'Smooth start, stable idle'],
                    ['name' => 'Acceleration', 'status' => 'good', 'notes' => 'Responsive throttle'],
                    ['name' => 'Transmission Shifting', 'status' => 'good', 'notes' => 'Smooth shifts through all gears'],
                    ['name' => 'Steering Response', 'status' => 'fair', 'notes' => 'Slight play at center position'],
                    ['name' => 'Braking Performance', 'status' => 'good', 'notes' => 'Straight stop, no pulling'],
                ],

                'power_train' => [
                    ['name' => 'Engine Oil', 'status' => 'good', 'notes' => 'Clean, at proper level'],
                    ['name' => 'Coolant Level', 'status' => 'good', 'notes' => 'Proper concentration'],
                    ['name' => 'Belts & Hoses', 'status' => 'fair', 'notes' => 'Serpentine belt has minor cracking'],
                    ['name' => 'Transmission Fluid', 'status' => 'good', 'notes' => 'Red/pink, no burnt smell'],
                ],

                'electrical_system' => [
                    ['name' => 'Battery', 'status' => 'good', 'notes' => '12.6V, CCA within spec'],
                    ['name' => 'Alternator Output', 'status' => 'good', 'notes' => '14.2V at idle'],
                    ['name' => 'Infotainment System', 'status' => 'poor', 'notes' => 'Screen is unresponsive intermittently'],
                    ['name' => 'HVAC Controls', 'status' => 'good', 'notes' => 'All functions work'],
                    ['name' => 'Power Windows', 'status' => 'fair', 'notes' => 'Passenger window slow to roll up'],
                ],

                'braking_safety' => [
                    ['name' => 'Front Brake Pads', 'status' => 'fair', 'notes' => '5mm remaining, replace soon'],
                    ['name' => 'Rear Brake Pads', 'status' => 'good', 'notes' => '8mm remaining'],
                    ['name' => 'Brake Rotors', 'status' => 'good', 'notes' => 'No warping or deep grooves'],
                    ['name' => 'ABS System', 'status' => 'good', 'notes' => 'No warning lights'],
                    ['name' => 'Parking Brake', 'status' => 'excellent', 'notes' => 'Holds properly'],
                ],

                'suspension' => [
                    ['name' => 'Shock Absorbers', 'status' => 'fair', 'notes' => 'Minor leaking on rear left'],
                    ['name' => 'Control Arm Bushings', 'status' => 'good', 'notes' => 'No excessive play'],
                    ['name' => 'Ball Joints', 'status' => 'good', 'notes' => 'No play'],
                    ['name' => 'Tie Rod Ends', 'status' => 'fair', 'notes' => 'Slight looseness on left side'],
                ],

                'ac_cooling' => [
                    ['name' => 'AC Operation', 'status' => 'good', 'notes' => 'Blows cold at 4°C vent temp'],
                    ['name' => 'AC Compressor', 'status' => 'excellent', 'notes' => 'Engages quietly'],
                    ['name' => 'Radiator Condition', 'status' => 'good', 'notes' => 'No leaks, fins clear'],
                    ['name' => 'Cooling Fan', 'status' => 'good', 'notes' => 'Both speeds working'],
                ],

                'all_notes' => [
                    ['note' => 'Customer reports intermittent infotainment screen freeze', 'section' => 'Electrical System'],
                    ['note' => 'Front tires should be replaced within 3 months', 'section' => 'Exterior'],
                    ['note' => 'Steering rack has very minor play - monitor', 'section' => 'Road Test'],
                ],

                'inspection_systems' => [
                    ['name' => 'OBD II Scan', 'status' => 'good'],
                    ['name' => 'SRS / Airbags', 'status' => 'good'],
                    ['name' => 'TPMS', 'status' => 'good'],
                    ['name' => 'ESC / Stability Control', 'status' => 'good'],
                    ['name' => 'Cruise Control', 'status' => 'not_checked'],
                ],
            ]
        );

        $this->command->info('Sample report seeded successfully!');
    }
}
