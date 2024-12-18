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
        Schema::create('product_option', function (Blueprint $table) {
            $table->id();
            $table->string('attribute_name'); // Tên thuộc tính (ví dụ: màu sắc, kích thước)
            $table->string('attribute_value'); // Giá trị của thuộc tính (ví dụ: đỏ, XL)
            $table->decimal('price', 10, 2)->nullable(); // Giá của thuộc tính (Ví dụ: topping có giá riêng)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_option');
    }
};
