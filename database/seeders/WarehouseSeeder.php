<?php

namespace Database\Seeders;

use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $purchases = Purchase::find(1);
        $storages = DB::table('warehouse_storages')->pluck('id');

        DB::table('warehouses')->insert([
            [
                'description' => 'Warehouse entry for material A',
                'purchase_id' => $purchases->id,
                'storage_id' => $storages->random(),
                'arrival_date' => Carbon::now()->subDays(4), // Tanggal 7 hari yang lalu
                'supplier_id' => $purchases->supplier_id,
                'user_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
