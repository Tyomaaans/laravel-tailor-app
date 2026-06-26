<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('measurements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('customer_id')->constrained()->cascadeOnDelete();;
            $table->integer('height')->nullable();
            $table->integer('weight')->nullable();
            $table->integer('neck')->nullable();
            $table->integer('chest')->nullable();
            $table->integer('waist')->nullable();
            $table->integer('hip')->nullable();
            $table->integer('shoulder')->nullable();
            $table->integer('sleeve_length')->nullable();
            $table->integer('shirt_length')->nullable();
            $table->integer('pants_length')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('measurements');
    }
};
