<?php

namespace App\Http\Controllers;

use App\Http\Resources\SupplierResources;
use Illuminate\Http\Request;
use App\Models\Supplier; // Pastikan Model Supplier diimpor
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    // Method untuk mengambil semua Supplier
    public function index(Request $request)
    {
        $Suppliers = Supplier::where('name', "like", "%" . $request->search . "%")->orWhere("description", "like", "%" . $request->search . "%")->latest()->paginate($request->limit ?? 10);
        return SupplierResources::collection($Suppliers);
    }

    // Method untuk menampilkan Supplier berdasarkan ID
    public function show($id)
    {
        $Supplier = Supplier::find($id);
        if ($Supplier) {
            return new SupplierResources($Supplier);
        } else {
            return response()->json(['message' => 'Supplier not found'], 404);
        }
    }

    // Method untuk membuat Supplier baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $Supplier = Supplier::create($request->all());
        return response()->json($Supplier, 201);
    }

    // Method untuk memperbarui Supplier berdasarkan ID
    public function update(Request $request, $id)
    {
        $Supplier = Supplier::find($id);
        if ($Supplier) {
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $Supplier->update($request->all());
            return new SupplierResources($Supplier);
        } else {
            return response()->json(['message' => 'Supplier not found'], 404);
        }
    }

    // Method untuk menghapus Supplier berdasarkan ID (soft delete)
    public function destroy($id)
    {
        $Supplier = Supplier::find($id);
        if ($Supplier) {
            $Supplier->delete(); // Soft delete
            return response()->json(['message' => 'Supplier deleted successfully']);
        } else {
            return response()->json(['message' => 'Supplier not found'], 404);
        }
    }

    // Method untuk memulihkan Supplier yang telah dihapus
    public function restore($id)
    {
        $Supplier = Supplier::onlyTrashed()->find($id);
        if ($Supplier) {
            $Supplier->restore(); // Restore soft deleted Supplier
            return new SupplierResources($Supplier);
        } else {
            return response()->json(['message' => 'Supplier not found'], 404);
        }
    }
}
