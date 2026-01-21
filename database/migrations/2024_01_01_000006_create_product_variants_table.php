<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('color_id')->nullable()->constrained('attribute_values')->onDelete('cascade');
            $table->foreignId('size_id')->nullable()->constrained('attribute_values')->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            $table->string('sku', 100)->unique()->nullable();
            $table->timestamps();
            
            $table->unique(['product_id', 'color_id', 'size_id'], 'unique_variant');
            $table->index('product_id');
            $table->index('color_id');
            $table->index('size_id');
            $table->index('quantity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
