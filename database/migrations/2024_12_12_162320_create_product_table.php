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
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên sản phẩm
            $table->unsignedBigInteger('category_id');
            $table->text('description')->nullable(); // Mô tả sản phẩm, có thể để null
            $table->decimal('price', 10, 2); // Giá sản phẩm, ví dụ: 99999999.99
            $table->text('image_url')->nullable(); // Đường dẫn ảnh của sản phẩm
            $table->timestamps();

            $table
                ->foreign('category_id')
                ->references('id')
                ->on('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
