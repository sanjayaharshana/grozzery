<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating test user...');

        // Check if user already exists
        $existingUser = User::where('email', 'user@user.com')->first();
        if ($existingUser) {
            $this->command->info('Test user already exists!');
            $this->command->info('Email: user@user.com');
            $this->command->info('Password: password');
            return;
        }

        // Create test user with specific credentials
        $user = User::create([
            'name' => 'Test User',
            'email' => 'user@user.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '+1234567890',
            'address' => '123 Test Street',
            'city' => 'Test City',
            'state' => 'TC',
            'zip_code' => '12345',
            'country' => 'USA',
        ]);

        $this->command->info('Test user created successfully!');
        $this->command->info('Email: user@user.com');
        $this->command->info('Password: password');
    }
}
