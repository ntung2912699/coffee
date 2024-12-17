<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_item extends Model
{
    use HasFactory;

    protected $table = 'order_item';

    // Các thuộc tính có thể mass assign
    protected $fillable = [
        'order_id', 'product_id', 'quantity', 'price', 'total_price', 'attributes'
    ];

    // Mối quan hệ với Order (mỗi OrderItem thuộc về một đơn hàng)
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Mối quan hệ với Product (mỗi OrderItem có thể chứa một sản phẩm)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
