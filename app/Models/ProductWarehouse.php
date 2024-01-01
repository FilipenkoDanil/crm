<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductWarehouse extends Pivot
{
    use HasFactory;

    protected $table = 'product_warehouse';
    protected $fillable = ['warehouse_id', 'product_id', 'stock', 'min_stock_notify'];
}
