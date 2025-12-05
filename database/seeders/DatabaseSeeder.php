<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
        ]);

        $this->command->info('🎉 All seeders completed successfully!');
        $this->command->info('');
        $this->command->info('📧 Login Credentials:');
        $this->command->info('Super Admin: superadmin@marketplace.com | password');
        $this->command->info('Admin: admin@marketplace.com | password');
        $this->command->info('Customer: john@example.com | password');
    }
}
