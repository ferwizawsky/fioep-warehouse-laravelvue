<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('warehouse_materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('generated_code')->unique();
            $table->string('quality')->default('good');
            $table->foreignId('material_id')->constrained('materials');
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade');;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_materials');
    }
};
