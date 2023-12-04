<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = ['title', 'slug', 'parent_id'];


    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id', 'id')->with(['subcategories']);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeMainParents(Builder $query)
    {
        return $query->whereNull('parent_id');
    }

    public static function show($slug): Category
    {
        return Category::where('slug', $slug)->with('products', 'parent', 'subcategories')->firstOrFail();
    }

    public function getAllSubcategoryIds(): array
    {
        $subcategoryIds = $this->subcategories()->pluck('id')->toArray();

        foreach ($this->subcategories as $subcategory) {
            $subcategoryIds = array_merge($subcategoryIds, $subcategory->getAllSubcategoryIds());
        }

        return $subcategoryIds;
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
