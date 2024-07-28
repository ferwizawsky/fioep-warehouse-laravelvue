<?php

namespace App\Http\Controllers;

use App\Exports\PurchasesExport;
use App\Http\Resources\PurchaseResources;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\PurchaseMaterial;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseController extends Controller
{

    public function export()
    {
        return Excel::download(new PurchasesExport, 'purchases.xlsx');
    }
    // Method untuk mengambil semua purchase
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $limit = $request->input('limit', 10);
        // Log::info('Search term:', ['search' => $search]);
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

    // Method untuk menampilkan purchase berdasarkan ID
    public function show($id)
    {
        $purchase = Purchase::with('materials')->find($id);
        if ($purchase) {
            return new PurchaseResources($purchase);
        } else {
            return response()->json(['message' => 'Purchase not found'], 404);
        }
    }

    // Method untuk membuat purchase baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'nullable|string',
            // 'user_id' => 'required|exists:users,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'approved_by' => 'nullable|exists:users,id',
            'approved_at' => 'nullable|date',
            // 'status' => 'required|in:pending,approved,rejected,processed,completed',
            'materials' => 'required|array',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $purchase = Purchase::create([
            'description' => $request->description,
            'user_id' => $request->user()?->id ?? 4,
            'supplier_id' => $request->supplier_id,
            'approved_by' => $request->approved_by ?? null,
            'approved_at' => $request->approved_at ?? null,
            // 'status' => $request->status,
            'status' => 'pending',
        ]);

        foreach ($request->materials as $material) {
            PurchaseMaterial::create([
                'purchase_id' => $purchase->id,
                'material_id' => $material['material_id'],
                'quantity' => $material['quantity'],
            ]);
        }
        return new PurchaseResources($purchase);
    }

    public function approval(Request $request, $id)
    {
        // return response()->json(['message' => 'Purchase no need to edit'], 401);

        $purchase = Purchase::find($id);
        if ($purchase && $purchase?->status == 'pending') {
            $validator = Validator::make($request->all(), [
                // 'description' => 'nullable|string',
                // 'user_id' => 'required|exists:users,id',
                // 'supplier_id' => 'required|exists:suppliers,id',
                // 'approved_by' => 'nullable|exists:users,id',
                // 'approved_at' => 'nullable|date',
                'status' => 'required|in:approved,rejected',
                // 'materials' => 'required|array',
                // 'materials.*.material_id' => 'required|exists:materials,id',
                // 'materials.*.quantity' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $purchase->update([
                'approved_by' => $request->user()?->id ?? 1,
                'approved_at' => now(),
                'status' => $request->status,
            ]);
            return new PurchaseResources($purchase);
        } else {
            return response()->json(['message' => 'Purchase not found'], 404);
        }
    }

    public function statusToProcessed(Request $request, $id)
    {
        $purchase = Purchase::find($id);
        if ($purchase) {
            if ($purchase->status != 'approved')      return response()->json(['message' => 'Purchase should be approved first !'], 401);
            $purchase->update([
                'status' => "processed",
            ]);
            return new PurchaseResources($purchase);
        } else {
            return response()->json(['message' => 'Purchase not found'], 404);
        }
    }


    // Method untuk menghapus purchase berdasarkan ID
    public function destroy($id)
    {
        $purchase = Purchase::find($id);
        if ($purchase) {
            $purchase->delete();
            return response()->json(['message' => 'Purchase deleted successfully']);
        } else {
            return response()->json(['message' => 'Purchase not found'], 404);
        }
    }
}
