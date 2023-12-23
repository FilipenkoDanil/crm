<?php

namespace App\Models;

use App\Models\Traits\Restorable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, Restorable;

    protected $fillable = ['title', 'barcode', 'code', 'image', 'category_id', 'purchase_price', 'selling_price'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($product) {
            $warehouses = Warehouse::all();
            $product->warehouses()->attach($warehouses);
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function warehouses(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class)->withPivot(['stock', 'min_stock_notify']);
    }
}
