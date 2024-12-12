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
        Schema::create('order_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('order'); // Khóa ngoại liên kết với bảng orders
            $table->foreignId('product_id')->constrained('product'); // Khóa ngoại liên kết với bảng products
            $table->integer('quantity'); // Số lượng sản phẩm trong đơn hàng
            $table->decimal('price', 10, 2); // Giá của sản phẩm tại thời điểm mua
            $table->decimal('total_price', 10, 2); // Tổng giá trị của sản phẩm trong đơn hàng
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_item');
    }
};
