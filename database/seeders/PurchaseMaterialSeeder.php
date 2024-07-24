<?php

namespace Database\Seeders;

use App\Models\Purchase;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchaseMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $purchase = Purchase::all();
        foreach ($purchase as $x) {
            DB::table('purchase_materials')->insert([
                [
                    'material_id' => 1,
                    'purchase_id' => $x->id,
                    'quantity' => 2,
                ],
                [
                    'material_id' => 2,
                    'purchase_id' => $x->id,
                    'quantity' => 2,
                ],
            ]);
        }
    }
}
