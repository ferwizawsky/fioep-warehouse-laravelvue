<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function user()
    {
        return $this->hasOne(User::class, "id", "user_id");
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class, "id", "supplier_id");
    }

    public function storage()
    {
        return $this->hasOne(WarehouseStorage::class, "id", "storage_id");
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class, "id", "purchase_id");
    }

    public function materials()
    {
        return $this->hasMany(WarehouseMaterial::class, "warehouse_id", "id");
    }
}
