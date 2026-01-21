<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['flash_deal', 'regular_sale', 'buy_x_get_y']);
            $table->enum('discount_type', ['percentage', 'fixed_amount']);
            $table->decimal('discount_value', 10, 2);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->enum('scope', ['product', 'collection', 'cart']);
            $table->integer('max_per_customer')->nullable();
            $table->integer('stop_when_stock_below')->default(0);
            $table->decimal('min_cart_value', 10, 2)->nullable();
            $table->boolean('exclude_new_products')->default(false);
            $table->integer('total_usage_count')->default(0);
            $table->integer('usage_limit')->nullable();
            $table->timestamps();
            
            $table->index(['start_date', 'end_date']);
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
