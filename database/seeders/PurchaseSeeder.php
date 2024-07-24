<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PurchaseSeeder extends Seeder
{
    public function run()
    {
        $suppliers = DB::table('suppliers')->pluck('id');
        $users = DB::table('users')->pluck('id');
        $purchaseDate = Carbon::now()->subDays(10); // Tanggal 10 hari yang lalu

        DB::table('purchases')->insert([
            [
                'supplier_id' => $suppliers->random(),
                'user_id' => 4,
                'approved_by' => 2,
                'approved_at' => $purchaseDate,
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_id' => $suppliers->random(),
                'status' => 'pending',
                'approved_by' => null,
                'approved_at' => null,
                'user_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
