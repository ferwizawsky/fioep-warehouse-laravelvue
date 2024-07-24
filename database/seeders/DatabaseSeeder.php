<?php

namespace Database\Seeders;

use App\Models\Supplier;
use App\Models\User;
use App\Models\WarehouseMaterial;
use App\Models\WarehouseStorage;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            MaterialSeeder::class,
            SupplierSeeder::class,
            WarehouseStorageSeeder::class,

            PurchaseSeeder::class,
            PurchaseMaterialSeeder::class,

            WarehouseSeeder::class,
            WarehouseMaterialSeeder::class,
        ]);
    }
}
