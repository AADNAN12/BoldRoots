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
        Schema::table('orders', function (Blueprint $table) {
            // Make user_id nullable for guest orders
            $table->unsignedBigInteger('user_id')->nullable()->change();
            
            // Add guest information fields
            $table->string('guest_name')->nullable()->after('user_id');
            $table->string('guest_email')->nullable()->after('guest_name');
            $table->string('guest_phone')->nullable()->after('guest_email');
            $table->string('guest_address_line1')->nullable()->after('guest_phone');
            $table->string('guest_address_line2')->nullable()->after('guest_address_line1');
            $table->string('guest_city')->nullable()->after('guest_address_line2');
            $table->string('guest_postal_code')->nullable()->after('guest_city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Remove guest fields
            $table->dropColumn([
                'guest_name',
                'guest_email',
                'guest_phone',
                'guest_address_line1',
                'guest_address_line2',
                'guest_city',
                'guest_postal_code'
            ]);
            
            // Make user_id not nullable again
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};
