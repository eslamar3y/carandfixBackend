<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $technician = Role::firstOrCreate(['name' => 'technician', 'guard_name' => 'web']);
        $customer = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);

        $admin->syncPermissions(Permission::all());

        $reportPerms = Permission::where('name', 'like', '%:Report')->get();
        $widgetPerms = Permission::whereIn('name', ['View:DateWidget', 'View:ClockWidget'])->get();
        $technician->syncPermissions($reportPerms->merge($widgetPerms));

        User::where('role', 'admin')->get()->each(fn($u) => $u->assignRole('admin'));
        User::where('role', 'technician')->get()->each(fn($u) => $u->assignRole('technician'));
        User::where('role', 'customer')->get()->each(fn($u) => $u->assignRole('customer'));

        $this->command->info('Roles and permissions assigned!');
    }
}
