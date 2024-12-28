<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockEntry extends Model
{
    use HasFactory;

    protected $table = 'stock_entries';

    protected $fillable = [
        'entry_date',
        'amount',
    ];
}
