<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseMaterialResources extends JsonResource
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
            'generated_code' => $this->generated_code,
            'quality' => $this->quality,
            'material' => new MaterialResources($this->material),
            'created_at' => $this->created_at,
            'warehouse' => new WarehouseResources($this->whenLoaded('warehouse')),
        ];
    }
}
