<?php

namespace Database\Seeders;

use App\Models\PricingModifier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PricingModifierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PricingModifier::insert([
            [
                'name' => '10% off on purchases over 100.00€',
                'type' => 'percentage',
                'value' => 0.10,
                'operation' => 'subtract',
                'scope' => 'order',
                'condition_function' => 'subtotalAboveThreshold'
            ],
            [
                'name' => 'Employee discount',
                'type' => 'percentage',
                'value' => 0.05,
                'operation' => 'subtract',
                'scope' => 'order',
                'condition_function' => 'employeeDiscount'
            ],
        ]);
    }
}
