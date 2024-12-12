<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';

    // Các thuộc tính có thể mass assign
    protected $fillable = [
        'customer_name', 'phone_number', 'status', 'total_price'
    ];

    // Mối quan hệ với CartItem (mỗi giỏ hàng có thể có nhiều sản phẩm)
    public function items()
    {
        return $this->hasMany(Cart_item::class);
    }
}
