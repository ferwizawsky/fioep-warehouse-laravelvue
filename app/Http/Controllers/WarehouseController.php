<?php

namespace App\Http\Controllers;

use App\Http\Resources\PurchaseResources;
use App\Http\Resources\WarehouseResources;
use App\Models\Purchase;
use App\Models\Warehouse;
use App\Models\WarehouseMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $limit = $request->input('limit', 10);
        $query = Warehouse::latest();
        if ($search) {
            $query->where('description', 'like', '%' . $search . '%');
        }
        $Warehouses = $query->paginate($limit);
        return WarehouseResources::collection($Warehouses);
    }

    public function indexPurchase(Request $request)
    {
        $search = $request->input('search');
        // $status = $request->input('status');
        $status = "processed";
        $limit = $request->input('limit', 10);

        $query = Purchase::with('materials')->latest();
        if ($search) {
            $query->where('description', 'like', '%' . $search . '%');
        }
        if ($status) {
            $query->where('status', '=', $status);
        }
        $purchases = $query->paginate($limit);
        return PurchaseResources::collection($purchases);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'nullable|string',
            'purchase_id' => 'required|exists:purchases,id',
            'storage_id' => 'required|exists:warehouse_storages,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $purchase = Purchase::with('materials')->find($request->purchase_id);
        if (!$purchase || $purchase?->status != 'approved') {
            return response()->json(["message" => "purchase not found"], 404);
        }

        $warehouse = Warehouse::create([
            'description' => $request->description,
            'purchase_id' => $request->purchase_id,
            'storage_id' => $request->storage_id,
            'arrival_date' => now(),
            'supplier_id' => $purchase->supplier_id,
            'user_id' => $request->user()?->id ?? 1,
        ]);

        foreach ($purchase?->materials as $material) {
            WarehouseMaterial::create([
                'warehouse_id' => $warehouse->id,
                'material_id' => $material->material_id,
                'generated_code' => $this->generateUniqueCode(),
            ]);
        }
        $purchase->update([
            'status' => "completed",
        ]);

        return new WarehouseResources($warehouse->load('purchase'));
    }



    public function show(string $id)
    {
        $warehouse = Warehouse::with('purchase')->find($id);
        if ($warehouse) {
            return new WarehouseResources($warehouse);
        } else {
            return response()->json(['message' => 'Warehouse not found'], 404);
        }
    }


    public function destroy($id)
    {
        $warehouse = Warehouse::find($id);
        if ($warehouse) {
            $warehouse->delete();
            return response()->json(['message' => 'Warehouse deleted successfully']);
        } else {
            return response()->json(['message' => 'Warehouse not found'], 404);
        }
    }


    private function generateUniqueCode()
    {
        do {
            // Generate a random length between 6 and 12
            $length = rand(6, 12);
            // Generate a random code of the specified length
            $code = Str::upper(Str::random($length));
        } while (DB::table('warehouse_materials')->where('generated_code', $code)->exists());

        return $code;
    }
}
