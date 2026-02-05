<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create one Super Admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@admin.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'company_id' => null,
                'branch_id' => null,
                'status' => true,
            ]
        );

        // Assign superadmin role
        if (!$superAdmin->hasRole('superadmin')) {
            $superAdmin->assignRole('superadmin');
        }
    }
}
