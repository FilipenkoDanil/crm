<?php

namespace App\Models;

use App\Models\Traits\Restorable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use HasFactory, SoftDeletes, Restorable;

    protected $fillable = ['title', 'address'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($warehouse) {
            $products = Product::all();
            $warehouse->products()->attach($products);
        });
    }


    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot(['stock', 'min_stock_notify']);
    }
}
