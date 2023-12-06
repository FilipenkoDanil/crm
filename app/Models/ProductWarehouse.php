<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductWarehouse extends Model
{
    use HasFactory;

    protected $table = 'product_warehouse';
    protected $fillable = ['warehouse_id', 'product_id', 'stock', 'min_stock_notify'];
}
