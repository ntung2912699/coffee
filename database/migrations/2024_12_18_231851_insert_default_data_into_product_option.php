<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('product_option')->insert([
            // Kích thước (Size)
            ['attribute_name' => 'Size', 'attribute_value' => 'M', 'price' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_name' => 'Size', 'attribute_value' => 'L', 'price' => 5000, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_name' => 'Size', 'attribute_value' => 'XL', 'price' => 10000, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_name' => 'Size', 'attribute_value' => '2XL', 'price' => 15000, 'created_at' => now(), 'updated_at' => now()],

            // Tùy chọn (Customization)
            ['attribute_name' => 'Đá', 'attribute_value' => '100% Đá', 'price' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_name' => 'Đá', 'attribute_value' => '70% Đá', 'price' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_name' => 'Đá', 'attribute_value' => '50% Đá', 'price' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_name' => 'Đá', 'attribute_value' => '30% Đá', 'price' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_name' => 'Đá', 'attribute_value' => '0% Đá', 'price' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_name' => 'Đường', 'attribute_value' => '100% Đường', 'price' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_name' => 'Đường', 'attribute_value' => '70% Đường', 'price' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_name' => 'Đường', 'attribute_value' => '50% Đường', 'price' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_name' => 'Đường', 'attribute_value' => '30% Đường', 'price' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_name' => 'Đường', 'attribute_value' => '0% Đường', 'price' => 0.00, 'created_at' => now(), 'updated_at' => now()],

            // Loại (Type)
            ['attribute_name' => 'Loại', 'attribute_value' => 'Nóng', 'price' => 0.00, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_name' => 'Loại', 'attribute_value' => 'Đá', 'price' => 0.00, 'created_at' => now(), 'updated_at' => now()],

            ['attribute_name' => 'Topping', 'attribute_value' => 'Nha Đam', 'price' => 5000, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_name' => 'Topping', 'attribute_value' => 'Trân Châu Đen', 'price' => 5000, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_name' => 'Topping', 'attribute_value' => 'Thạch Kim Cương Đen', 'price' => 5000, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_name' => 'Topping', 'attribute_value' => 'Kem Trứng Nướng', 'price' => 5000, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_name' => 'Topping', 'attribute_value' => 'Kem Chuối', 'price' => 5000, 'created_at' => now(), 'updated_at' => now()],
            ['attribute_name' => 'Topping', 'attribute_value' => 'Hạt Nổ', 'price' => 5000, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('product_option')
            ->whereIn('attribute_name', ['Size', 'Đá', 'Đường', 'Loại', 'Topping'])
            ->delete();
    }
};
