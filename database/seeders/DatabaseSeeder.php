<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        User::create([
            'name' => 'Admin',
            'email' => 'admin@clickandfix.com',
            'password' => 'password',
            'phone' => '1234567890',
            'role' => 'admin',
            'is_verified' => true,
            'is_active' => true,
            'email_verified_at' => $now,
        ]);

        User::create([
            'name' => 'Technician',
            'email' => 'tech@clickandfix.com',
            'password' => 'password',
            'phone' => '1234567891',
            'role' => 'technician',
            'is_verified' => true,
            'is_active' => true,
            'email_verified_at' => $now,
        ]);

        User::create([
            'name' => 'Customer',
            'email' => 'customer@clickandfix.com',
            'password' => 'password',
            'phone' => '1234567892',
            'role' => 'customer',
            'is_verified' => true,
            'is_active' => true,
            'email_verified_at' => $now,
        ]);

        $this->call(CatalogSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);

        // $this->call(TestDataSeeder::class);
    }
}
