<?php

namespace App\Http\Controllers;

use App\Http\Resources\WarehouseMaterialResources;
use Illuminate\Http\Request;
use App\Models\WarehouseMaterial; // Pastikan Model WarehouseMaterial diimpor
use Illuminate\Support\Facades\Validator;

class WarehouseMaterialController extends Controller
{
    // Method untuk mengambil semua warehousematerial
    public function index(Request $request)
    {
        $warehousematerials = WarehouseMaterial::where('generated_code', "like", "%" . $request->search . "%")
            ->orWhere("code", "like", "%" . $request->search . "%")->latest()->paginate($request->limit ?? 10);
        return WarehouseMaterialResources::collection($warehousematerials);
    }

    public function show($id)
    {
        $warehousematerial = WarehouseMaterial::with('warehouse')->find($id);
        if ($warehousematerial) {
            return new WarehouseMaterialResources($warehousematerial);
        } else {
            return response()->json(['message' => 'WarehouseMaterial not found'], 404);
        }
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'description' => 'nullable|string',
            // 'user_id' => 'required|exists:users,id',
            // 'supplier_id' => 'required|exists:suppliers,id',
            // 'approved_by' => 'nullable|exists:users,id',
            // 'approved_at' => 'nullable|date',
            // 'status' => 'required|in:approved,rejected',
            'warehouse_material' => 'required|array',
            'warehouse_material.*.id' => 'required|exists:warehouse_materials,id',
            'warehouse_material.*.quality' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        foreach ($request->warehouse_material as $item) {
            $material = WarehouseMaterial::find($item['id']);
            $material->update([
                'quality' => $item['quality'],
            ]);
        }

        return response()->json(["message" => "success"]);
    }
}
