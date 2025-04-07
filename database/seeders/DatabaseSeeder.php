<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\PaymentType;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductSellingType;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

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
            [
                'name' => 'CREDIT',
            ],
        ];

        foreach ($pmtType as $pmt) {
            PaymentType::factory()->create($pmt);
        }

        $user = User::factory()->create([
            'name' => 'Wizbiz Admin',
            'email' => 'admin@wizbizgh.com',
            'password' => Hash::make('F!rstuser'),
            'disabled' => false
        ]);

        $this->call(RolePermissionSeeder::class);
        $adminRole = Role::firstOrCreate(['name' => 'super-admin']);

        $permissions = Permission::all();

        $adminRole->syncPermissions($permissions);

        if (!$user->hasRole('super-admin')) {
            $user->assignRole($adminRole);
        }

        $role = $user->roles()->first();

        $role->syncPermissions($permissions);

    }
}
