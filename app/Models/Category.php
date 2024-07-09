<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product', 'category_id', 'SKU');
    }

    public function vatPercentages()
    {
        return $this->belongsToMany(Country::class, 'country_category', 'category_id', 'country_id')
            ->withPivot('vat_percentage');
    }

    public function getAllSubcategories()
    {
        $subcategories = collect([$this]); // Start with the current category

        foreach ($this->children as $childCategory) {
            $subcategories = $subcategories->merge($childCategory->getAllSubcategories());
        }

        return $subcategories;
    }
}
