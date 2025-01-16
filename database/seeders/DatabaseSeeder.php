<?php

namespace Database\Seeders;

use App\Models\PaymentType;
use App\Models\ProductSellingType;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

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
                'selling_type' => 'Box',
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

        $pmtType = [
            [
                'name' => 'CASH',
            ],
            [
                'name' => 'POS',
            ],
            [
                'name' => 'MOBILE_MONEY',
            ],
        ];

        foreach ($pmtType as $pmt) {
            PaymentType::factory()->create($pmt);
        }
    }
}
