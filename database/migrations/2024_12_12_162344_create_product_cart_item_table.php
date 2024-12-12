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
        Schema::create('cart_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('cart'); // Khóa ngoại liên kết với bảng carts
            $table->foreignId('product_id')->constrained('product'); // Khóa ngoại liên kết với bảng products
            $table->integer('quantity'); // Số lượng sản phẩm trong giỏ hàng
            $table->decimal('price', 10, 2); // Giá sản phẩm tại thời điểm thêm vào giỏ hàng
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_item');
    }
};
