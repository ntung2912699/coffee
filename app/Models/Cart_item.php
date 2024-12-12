<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart_item extends Model
{
    use HasFactory;

    protected $table = 'cart_item';

    // Các thuộc tính có thể mass assign
    protected $fillable = [
        'cart_id', 'product_id', 'quantity', 'price'
    ];

    // Mối quan hệ với Cart
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    // Mối quan hệ với Product (mỗi CartItem có thể chứa một sản phẩm)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
