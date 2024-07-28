<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function approved()
    {
        return $this->hasOne(User::class, "id", "approved_by");
    }

    public function user()
    {
        return $this->hasOne(User::class, "id", "user_id");
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class, "id", "supplier_id");
    }
    public function materials()
    {
        return $this->hasMany(PurchaseMaterial::class);
    }
}
