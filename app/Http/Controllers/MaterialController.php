<?php

namespace App\Http\Controllers;

use App\Http\Resources\MaterialResources;
use Illuminate\Http\Request;
use App\Models\Material; // Pastikan Model Material diimpor
use Illuminate\Support\Facades\Validator;

class MaterialController extends Controller
{
    // Method untuk mengambil semua material
    public function index(Request $request)
    {
        $materials = Material::where('name', "like", "%" . $request->search . "%")->orWhere("code", "like", "%" . $request->search . "%")->latest()->paginate($request->limit ?? 10);
        return MaterialResources::collection($materials);
    }

    // Method untuk menampilkan material berdasarkan ID
    public function show($id)
    {
        $material = Material::find($id);
        if ($material) {
            return new MaterialResources($material);
        } else {
            return response()->json(['message' => 'Material not found'], 404);
        }
    }

    // Method untuk membuat material baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|unique:materials,code|max:255',
            'img' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $material = Material::create($request->all());
        return response()->json($material, 201);
    }

    // Method untuk memperbarui material berdasarkan ID
    public function update(Request $request, $id)
    {
        $material = Material::find($id);
        if ($material) {
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'code' => 'nullable|string|unique:materials,code,' . $id . '|max:255',
                'img' => 'nullable|url',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $material->update($request->all());
            return new MaterialResources($material);
        } else {
            return response()->json(['message' => 'Material not found'], 404);
        }
    }

    // Method untuk menghapus material berdasarkan ID (soft delete)
    public function destroy($id)
    {
        $material = Material::find($id);
        if ($material) {
            $material->delete(); // Soft delete
            return response()->json(['message' => 'Material deleted successfully']);
        } else {
            return response()->json(['message' => 'Material not found'], 404);
        }
    }

    // Method untuk memulihkan material yang telah dihapus
    public function restore($id)
    {
        $material = Material::onlyTrashed()->find($id);
        if ($material) {
            $material->restore(); // Restore soft deleted material
            return new MaterialResources($material);
        } else {
            return response()->json(['message' => 'Material not found'], 404);
        }
    }
}
