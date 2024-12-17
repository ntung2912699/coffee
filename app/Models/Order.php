<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';

    // Các thuộc tính có thể mass assign
    protected $fillable = [
        'customer_name', 'phone_number', 'address', 'status', 'total_price', 'order_date'
    ];

    // Mối quan hệ với OrderItem (mỗi đơn hàng có thể có nhiều sản phẩm)
    public function items()
    {
        return $this->hasMany(Order_item::class);
    }
}
