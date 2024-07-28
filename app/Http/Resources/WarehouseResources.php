<?php

namespace App\Http\Resources;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'user' => new UserResource($this->user),
            'supplier' => new SupplierResources($this->supplier),
            'storage' => new WarehouseStorageResources($this->storage),
            'purchase' => new PurchaseResources($this->whenLoaded('purchase')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'materials' => WarehouseMaterialResources::collection($this->materials),
        ];
    }
}
