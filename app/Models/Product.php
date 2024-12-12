<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';

    // Các thuộc tính có thể mass assign
    protected $fillable = [
        'name', 'category_id', 'description', 'price', 'image_url'
    ];

    // Mối quan hệ với ProductAttribute
    public function attributes()
    {
        return $this->hasMany(Product_option::class);
    }

    // Mối quan hệ với OrderItem (một sản phẩm có thể nằm trong nhiều đơn hàng)
    public function orderItems()
    {
        return $this->hasMany(Order_item::class);
    }

    // Mối quan hệ với Category (nếu sản phẩm thuộc một danh mục)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
