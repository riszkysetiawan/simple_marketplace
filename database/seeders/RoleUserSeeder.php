<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleUserSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ===== CREATE ONLY 2 ROLES =====

        $superAdmin = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        $customer = Role::firstOrCreate([
            'name' => 'customer',
            'guard_name' => 'web',
        ]);

        $this->command->info('✅ Created 2 roles: super_admin, customer');

        // ===== CREATE PERMISSIONS =====

        $permissions = [
            // Product
            'view_any_product',
            'view_product',
            'create_product',
            'update_product',
            'delete_product',

            // Category
            'view_any_category',
            'view_category',
            'create_category',
            'update_category',
            'delete_category',

            // Transaction
            'view_any_transaction',
            'view_transaction',
            'create_transaction',
            'update_transaction',
            'delete_transaction',

            // User
            'view_any_user',
            'view_user',
            'create_user',
            'update_user',
            'delete_user',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $this->command->info('✅ Created ' . count($permissions) . ' permissions');

        // ===== ASSIGN PERMISSIONS TO ROLES =====

        // Super Admin: ALL permissions
        $superAdmin->syncPermissions(Permission::all());
        $this->command->info('✅ Super Admin has all permissions');

        // Customer: Read-only permissions
        $customer->syncPermissions([
            'view_any_product',
            'view_product',
            'view_any_category',
            'view_category',
            'view_any_transaction',
            'view_transaction',
        ]);
        $this->command->info('✅ Customer has read permissions');

        // ===== ASSIGN ROLES TO USERS =====

        // Option 1: Assign by email (manual)
        $adminEmails = [
            'admin@example.com',
            // Add more admin emails here
        ];

        foreach ($adminEmails as $email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->syncRoles(['super_admin']);
                $this->command->info("✅ {$email} → super_admin");
            }
        }

        // Option 2: All other users = customer
        $otherUsers = User::whereNotIn('email', $adminEmails)->get();
        foreach ($otherUsers as $user) {
            $user->syncRoles(['customer']);
            $this->command->info("✅ {$user->email} → customer");
        }

        // ===== SUMMARY =====

        $this->command->info("\n=== SUMMARY ===");
        $this->command->info("Super Admins: " . User::role('super_admin')->count());
        $this->command->info("Customers: " . User::role('customer')->count());
        $this->command->info("Users without role: " . User::doesntHave('roles')->count());
    }
}
