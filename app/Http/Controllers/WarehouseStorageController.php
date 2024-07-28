<?php

namespace App\Http\Controllers;

use App\Http\Resources\WarehouseStorageResources;
use Illuminate\Http\Request;
use App\Models\WarehouseStorage; // Pastikan Model WarehouseStorage diimpor
use Illuminate\Support\Facades\Validator;

class WarehouseStorageController extends Controller
{
    // Method untuk mengambil semua WarehouseStorage
    public function index(Request $request)
    {
        $WarehouseStorages = WarehouseStorage::where('name', "like", "%" . $request->search . "%")->orWhere("code", "like", "%" . $request->search . "%")->latest()->paginate($request->limit ?? 10);
        return WarehouseStorageResources::collection($WarehouseStorages);
    }

    // Method untuk menampilkan WarehouseStorage berdasarkan ID
    public function show($id)
    {
        $WarehouseStorage = WarehouseStorage::find($id);
        if ($WarehouseStorage) {
            return new WarehouseStorageResources($WarehouseStorage);
        } else {
            return response()->json(['message' => 'WarehouseStorage not found'], 404);
        }
    }

    // Method untuk membuat WarehouseStorage baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $WarehouseStorage = WarehouseStorage::create($request->all());
        return response()->json($WarehouseStorage, 201);
    }

    // Method untuk memperbarui WarehouseStorage berdasarkan ID
    public function update(Request $request, $id)
    {
        $WarehouseStorage = WarehouseStorage::find($id);
        if ($WarehouseStorage) {
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $WarehouseStorage->update($request->all());
            return new WarehouseStorageResources($WarehouseStorage);
        } else {
            return response()->json(['message' => 'WarehouseStorage not found'], 404);
        }
    }

    // Method untuk menghapus WarehouseStorage berdasarkan ID (soft delete)
    public function destroy($id)
    {
        $WarehouseStorage = WarehouseStorage::find($id);
        if ($WarehouseStorage) {
            $WarehouseStorage->delete(); // Soft delete
            return response()->json(['message' => 'WarehouseStorage deleted successfully']);
        } else {
            return response()->json(['message' => 'WarehouseStorage not found'], 404);
        }
    }

    // Method untuk memulihkan WarehouseStorage yang telah dihapus
    public function restore($id)
    {
        $WarehouseStorage = WarehouseStorage::onlyTrashed()->find($id);
        if ($WarehouseStorage) {
            $WarehouseStorage->restore(); // Restore soft deleted WarehouseStorage
            return new WarehouseStorageResources($WarehouseStorage);
        } else {
            return response()->json(['message' => 'WarehouseStorage not found'], 404);
        }
    }
}
