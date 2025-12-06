<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        $superAdmin = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        $customer = Role::firstOrCreate([
            'name' => 'customer',
            'guard_name' => 'web',
        ]);

        $this->command->info('✅ Created roles');


        $allPermissions = Permission::all();
        $superAdmin->syncPermissions($allPermissions);

        $this->command->info("✅ Super admin has {$allPermissions->count()} permissions");

        $customerPermissions = Permission::where('name', 'like', 'view%')->get();
        $customer->syncPermissions($customerPermissions);

        $this->command->info("✅ Customer has {$customerPermissions->count()} permissions");
    }
}
