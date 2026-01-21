<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('delivery_number', 50)->unique();
            $table->date('delivery_date');
            $table->string('carrier_name')->nullable();
            $table->string('tracking_number')->nullable();
            $table->enum('status', ['pending', 'in_transit', 'delivered', 'failed', 'returned'])->default('pending');
            $table->timestamp('delivered_at')->nullable();
            $table->string('recipient_name')->nullable();
            $table->string('signature_image')->nullable();
            $table->string('pdf_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('delivery_number');
            $table->index('order_id');
            $table->index('tracking_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_notes');
    }
};
