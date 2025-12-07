<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1.Super Admin User
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'rizkysetiawann22@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'phone' => '081234567890',
            'address' => 'Jakarta, Indonesia',
        ]);
        $superAdmin->assignRole('super_admin');

        $customers = [
            ['name' => 'John Doe', 'email' => 'john@example.com'],
            ['name' => 'Jane Smith', 'email' => 'jane@example.com'],
            ['name' => 'Bob Wilson', 'email' => 'bob@example.com'],
            ['name' => 'Alice Brown', 'email' => 'alice@example.com'],
            ['name' => 'Charlie Davis', 'email' => 'charlie@example.com'],
        ];

        foreach ($customers as $customerData) {
            $customer = User::create([
                'name' => $customerData['name'],
                'email' => $customerData['email'],
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'phone' => '0812' . rand(10000000, 99999999),
                'address' => fake()->address(),
            ]);
            $customer->assignRole('customer');
        }

        $this->command->info('✅ Users created successfully!');
        $this->command->info('📧 Super Admin: superadmin@marketplace.com | password');
        $this->command->info('📧 Customer: john@example.com | password');
    }
}
