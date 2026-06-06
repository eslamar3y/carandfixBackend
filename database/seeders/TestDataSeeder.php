<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // 1. Customers
        $customer1 = User::create([
            'name' => 'Ahmed Ali',
            'email' => 'ahmed@test.com',
            'password' => 'password',
            'phone' => '01000000001',
            'role' => 'customer',
            'is_verified' => true,
            'is_active' => true,
            'email_verified_at' => $now,
        ]);

        $customer2 = User::create([
            'name' => 'Mohamed Hassan',
            'email' => 'mohamed@test.com',
            'password' => 'password',
            'phone' => '01000000002',
            'role' => 'customer',
            'is_verified' => true,
            'is_active' => true,
            'email_verified_at' => $now,
        ]);

        $customer3 = User::create([
            'name' => 'Sara Khaled',
            'email' => 'sara@test.com',
            'password' => 'password',
            'phone' => '01000000003',
            'role' => 'customer',
            'is_verified' => true,
            'is_active' => false,
            'email_verified_at' => $now,
        ]);

        $customer4 = User::create([
            'name' => 'Omar Youssef',
            'email' => 'omar@test.com',
            'password' => 'password',
            'phone' => '01000000004',
            'role' => 'customer',
            'is_verified' => false,
            'is_active' => false,
            'email_verified_at' => null,
        ]);

        // 2. Technician
        $tech1 = User::create([
            'name' => 'Kareem Mostafa',
            'email' => 'kareem@test.com',
            'password' => 'password',
            'phone' => '01000000005',
            'role' => 'technician',
            'is_verified' => true,
            'is_active' => true,
            'email_verified_at' => $now,
        ]);

        // 3. Cars
        $car1 = Car::create([
            'user_id' => $customer1->id,
            'vin_number' => 'WBA3A5C5XDF123456',
            'car_type_id' => 1,
            'car_sub_type_id' => 1,
            'engine_type_id' => 2,
            'status' => 'approved',
            'color' => 'White',
            'registration_number' => 'ABC123',
            'year_of_production' => '2020',
            'engine_power' => '150 HP',
            'last_oil_change_date' => '2025-12-01',
        ]);

        $car2 = Car::create([
            'user_id' => $customer1->id,
            'vin_number' => 'WBA3A5C5XDF654321',
            'car_type_id' => 3,
            'car_sub_type_id' => 7,
            'engine_type_id' => 3,
            'status' => 'pending',
            'color' => 'Black',
            'registration_number' => 'DEF456',
            'year_of_production' => '2022',
            'engine_power' => '200 HP',
        ]);

        $car3 = Car::create([
            'user_id' => $customer2->id,
            'vin_number' => 'JTDKB20U993456789',
            'car_type_id' => 1,
            'car_sub_type_id' => 2,
            'engine_type_id' => 1,
            'status' => 'approved',
            'color' => 'Red',
            'registration_number' => 'GHI789',
            'year_of_production' => '2021',
            'engine_power' => '120 HP',
        ]);

        $car4 = Car::create([
            'user_id' => $customer2->id,
            'vin_number' => 'JTDKB20U993987654',
            'car_type_id' => 4,
            'car_sub_type_id' => 10,
            'engine_type_id' => 2,
            'status' => 'rejected',
            'color' => 'Blue',
            'registration_number' => 'JKL012',
            'year_of_production' => '2019',
            'engine_power' => '180 HP',
        ]);

        $car5 = Car::create([
            'user_id' => $customer3->id,
            'vin_number' => '5YJSA1E26KF345678',
            'car_type_id' => 2,
            'car_sub_type_id' => 4,
            'engine_type_id' => 4,
            'status' => 'pending',
            'color' => 'Silver',
            'registration_number' => 'MNO345',
            'year_of_production' => '2023',
            'engine_power' => '250 HP',
        ]);

        // 4. Orders with different statuses
        Order::create([
            'user_id' => $customer1->id,
            'car_id' => $car1->id,
            'price' => 150.00,
            'phone' => '01000000001',
            'status' => 'completed',
            'type' => 'Emergency',
            'item_id' => 1,
            'item_name' => 'Towing',
            'technician_id' => $tech1->id,
            'created_at' => now()->subDays(5),
        ]);

        Order::create([
            'user_id' => $customer1->id,
            'car_id' => $car1->id,
            'price' => 80.00,
            'phone' => '01000000001',
            'status' => 'in_progress',
            'type' => 'Services',
            'item_id' => 2,
            'item_name' => 'Brake Pads Replacement',
            'technician_id' => $tech1->id,
            'created_at' => now()->subDays(1),
        ]);

        Order::create([
            'user_id' => $customer2->id,
            'car_id' => $car3->id,
            'price' => 200.00,
            'phone' => '01000000002',
            'status' => 'pending',
            'type' => 'Services',
            'item_id' => 1,
            'item_name' => 'Oil Change',
            'created_at' => now()->subHours(5),
        ]);

        Order::create([
            'user_id' => $customer2->id,
            'car_id' => $car3->id,
            'price' => 50.00,
            'phone' => '01000000002',
            'status' => 'accepted',
            'type' => 'Emergency',
            'item_id' => 4,
            'item_name' => 'Jump Start',
            'technician_id' => $tech1->id,
            'created_at' => now()->subDays(2),
        ]);

        Order::create([
            'user_id' => $customer3->id,
            'car_id' => $car5->id,
            'price' => 300.00,
            'phone' => '01000000003',
            'status' => 'cancelled',
            'type' => 'Parts',
            'item_id' => 1,
            'item_name' => 'Premium Batteries',
            'created_at' => now()->subDays(10),
        ]);

        Order::create([
            'user_id' => $customer1->id,
            'car_id' => $car1->id,
            'price' => 120.00,
            'phone' => '01000000001',
            'status' => 'completed',
            'type' => 'Services',
            'item_id' => 3,
            'item_name' => 'AC Gas Refill',
            'technician_id' => $tech1->id,
            'created_at' => now()->subDays(7),
        ]);

        Order::create([
            'user_id' => $customer2->id,
            'car_id' => $car3->id,
            'price' => 450.00,
            'phone' => '01000000002',
            'status' => 'completed',
            'type' => 'Parts',
            'item_id' => 4,
            'item_name' => 'Brake Pads',
            'technician_id' => $tech1->id,
            'created_at' => now()->subDays(15),
        ]);

        Order::create([
            'user_id' => $customer2->id,
            'car_id' => $car3->id,
            'price' => 100.00,
            'phone' => '01000000002',
            'status' => 'completed',
            'type' => 'Emergency',
            'item_id' => 2,
            'item_name' => 'Flat Tire',
            'technician_id' => $tech1->id,
            'created_at' => now()->subDays(3),
        ]);

        $this->command->info('Test data seeded successfully!');
    }
}
