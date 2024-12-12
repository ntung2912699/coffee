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
        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('phone_number')->nullable();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending'); // Trạng thái đơn hàng
            $table->decimal('total_price', 10, 2);
            $table->timestamp('order_date')->useCurrent(); // Ngày đặt hàng
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
