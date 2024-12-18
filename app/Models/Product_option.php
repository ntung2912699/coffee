<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_option extends Model
{

    use HasFactory;

    protected $table = 'product_option';

    // Các thuộc tính có thể mass assign
    protected $fillable = [
        'attribute_name', 'attribute_value', 'price'
    ];
}
