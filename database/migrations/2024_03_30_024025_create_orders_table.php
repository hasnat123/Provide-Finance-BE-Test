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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('invoice_number', 31)->unique()->index();
            $table->decimal('total', 8, 2);
            $table->enum(
                'status',
                ['IN_CART', 'PENDING', 'SUCCESS', 'FAILED']
            )->index();
            $table->json('shipping_address'); // Shipping address details
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
