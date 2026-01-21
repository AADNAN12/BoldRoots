<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buy_x_get_y_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained()->onDelete('cascade');
            $table->integer('buy_quantity');
            $table->integer('get_quantity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buy_x_get_y_rules');
    }
};
