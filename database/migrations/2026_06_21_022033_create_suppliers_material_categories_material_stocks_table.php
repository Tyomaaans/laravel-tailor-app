<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('contact_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('material_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name'); // "Kain", "Benang", "Aksesoris"
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('material_stocks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('category_id')->constrained('material_categories')->cascadeOnDelete();
            $table->string('name'); // "Kain Katun Putih", "Benang Hitam"
            $table->decimal('quantity', 10, 2)->default(0);
            $table->string('unit')->default('meter'); // meter, yard, pcs, roll
            $table->decimal('cost_per_unit', 12, 2)->default(0);
            $table->integer('min_stock')->default(0); // alert jika di bawah ini
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier');
        Schema::dropIfExists('material_categories');
        Schema::dropIfExists('material_stocks');
    }
};
