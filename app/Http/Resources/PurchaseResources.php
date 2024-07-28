<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'user' => new UserResource($this->user),
            'supplier' => new SupplierResources($this->supplier),
            'approved_by' => new UserResource($this->approved),
            'approved_at' => $this->approved_at,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'materials' => PurchaseMaterialResources::collection($this->materials),
        ];
    }
}
