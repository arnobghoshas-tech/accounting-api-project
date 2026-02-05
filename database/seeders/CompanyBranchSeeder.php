<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Branch;
use Illuminate\Database\Seeder;

class CompanyBranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 3 dummy companies
        Company::factory()
            ->count(3)
            ->create()
            ->each(function ($company) {
                // For each company, create 2 branches
                Branch::factory()
                    ->count(2)
                    ->create([
                        'company_id' => $company->id,
                    ]);
            });
    }
}
