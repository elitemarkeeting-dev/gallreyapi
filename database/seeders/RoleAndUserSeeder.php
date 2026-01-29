<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleAndUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $admin = \App\Models\Role::create([
            'name' => 'Admin',
            'description' => 'Administrator with full access',
        ]);

        $user = \App\Models\Role::create([
            'name' => 'User',
            'description' => 'Regular user',
        ]);

        $moderator = \App\Models\Role::create([
            'name' => 'Moderator',
            'description' => 'Moderator role',
        ]);

        // Create users
        \App\Models\User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role_id' => $admin->id,
        ]);

        \App\Models\User::create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'role_id' => $user->id,
        ]);

        \App\Models\User::create([
            'name' => 'Moderator User',
            'username' => 'moderator',
            'email' => 'moderator@example.com',
            'password' => bcrypt('password'),
            'role_id' => $moderator->id,
        ]);
    }
}
