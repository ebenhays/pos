<?php

namespace Database\Seeders;

use App\Models\ProductSellingType;
use App\Models\ProductUnit;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $productUnit = [
            [
                'prod_desc' => 'Single Item',
                'prod_unit_code' => 1000
            ],
            [
                'prod_desc' => 'Box',
                'prod_unit_code' => 1002
            ],
            [
                'prod_desc' => 'Pack',
                'prod_unit_code' => 1003
            ],
            [
                'prod_desc' => 'Carton',
                'prod_unit_code' => 1004
            ],
            [
                'prod_desc' => 'Bottle',
                'prod_unit_code' => 1005
            ],
        ];
        foreach ($productUnit as $value) {
            ProductUnit::factory()->create($value);
        }

        $sellingType = [
            [
                'selling_type' => 'WholeSale',
                'selling_code' => 'WHL'
            ],
            [
                'selling_type' => 'Retail',
                'selling_code' => 'RTL'
            ],
            [
                'selling_type' => 'BOX',
                'selling_code' => 'BX'
            ],
            [
                'selling_type' => 'Kilos',
                'selling_code' => 'KG'
            ],
        ];

        foreach ($sellingType as $value) {
            ProductSellingType::factory()->create($value);
        }
    }
}
