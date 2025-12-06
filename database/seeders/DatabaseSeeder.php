<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');
        $this->command->newLine();

        $this->command->info('📌 Step 1: Creating roles & permissions...');
        $this->call(RoleSeeder::class);
        $this->command->newLine();

        $this->command->info('📌 Step 2: Creating users...');
        $this->call(UserSeeder::class);
        $this->command->newLine();

        $this->command->info('📌 Step 3: Creating categories...');
        $this->call(CategorySeeder::class);
        $this->command->newLine();

        $this->command->info('📌 Step 4: Creating products (this may take a while)...');
        $startTime = microtime(true);
        $this->call(ProductSeeder::class);
        $endTime = microtime(true);
        $executionTime = round($endTime - $startTime, 2);
        $this->command->info("⏱️  Products created in {$executionTime} seconds");
        $this->command->newLine();

        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->newLine();
        $this->command->info('📊 Summary:');
        $this->command->info('   - Roles: 2 (super_admin, customer)');
        $this->command->info('   - Users: 6 (1 admin, 5 customers)');
        $this->command->info('   - Categories: 10');
        $this->command->info('   - Products: 2000 (200 per category)');
        $this->command->newLine();
        $this->command->info('🔐 Login Credentials:');
        $this->command->info('   Super Admin: superadmin@marketplace.com | password');
        $this->command->info('   Customer: john@example.com | password');
    }
}
