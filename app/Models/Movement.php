<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    use HasFactory;

    protected $fillable = ['movementable_id', 'movementable_type', 'product_id', 'warehouse_id', 'quantity', 'unit_price'];

    public function movementable()
    {
        return $this->morphTo();
    }
}
