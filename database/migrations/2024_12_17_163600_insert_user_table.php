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
        DB::statement("
        INSERT INTO \"users\" (\"id\", \"name\", \"email\", \"email_verified_at\", \"password\", \"remember_token\", \"created_at\", \"updated_at\")
        VALUES ('1', 'ADMIN COFFEE GIÓ', 'admincoffeegio@gmail.com', NULL, '$2y$12$5t12q7G8inkSDtmYrhWMp.n7bdZ03hsKhak4DRcOnB0jwo6fudx/W', NULL, NOW(), NOW())
    ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Xoá dữ liệu đã thêm trong hàm up()
        DB::statement("DELETE FROM \"users\" WHERE \"id\" = '1'");
    }
};
