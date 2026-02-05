<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BankSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            'Sonali Bank Limited',
            'Janata Bank Limited',
            'Agrani Bank Limited',
            'Rupali Bank Limited',
            'Bangladesh Krishi Bank',
            'Rajshahi Krishi Unnayan Bank',
            'Probashi Kallyan Bank',
            'Bangladesh Development Bank Limited',
            'AB Bank Limited',
            'Bank Asia Limited',
            'BRAC Bank Limited',
            'City Bank Limited',
            'Dhaka Bank Limited',
            'Dutch-Bangla Bank Limited',
            'Eastern Bank Limited',
            'IFIC Bank Limited',
            'Jamuna Bank Limited',
            'Meghna Bank Limited',
            'Mercantile Bank Limited',
            'Midland Bank Limited',
            'Modhumoti Bank Limited',
            'Mutual Trust Bank Limited',
            'National Bank Limited',
            'NRB Bank Limited',
            'NRB Commercial Bank Limited',
            'NRB Global Bank Limited',
            'One Bank Limited',
            'Padma Bank Limited',
            'Prime Bank Limited',
            'Pubali Bank Limited',
            'Shahjalal Islami Bank Limited',
            'Southeast Bank Limited',
            'Standard Bank Limited',
            'Trust Bank Limited',
            'United Commercial Bank Limited',
            'Uttara Bank Limited',
            'Islami Bank Bangladesh Limited',
            'Al-Arafah Islami Bank Limited',
            'EXIM Bank Limited',
            'First Security Islami Bank Limited',
            'Global Islami Bank Limited',
            'ICB Islamic Bank Limited',
            'Social Islami Bank Limited',
            'Union Bank Limited',
            'Citibank N.A.',
            'HSBC',
            'Standard Chartered Bank',
            'Woori Bank',
            'Commercial Bank of Ceylon',
        ];

        foreach ($banks as $bank) {
            Bank::firstOrCreate(['name' => $bank]);
        }
    }
}
