<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $primaryKey = 'SKU';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['SKU', 'name', 'description', 'price', 'published_at'];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product', 'SKU', 'category_id');
    }

    public function priceLists()
    {
        return $this->belongsToMany(PriceList::class, 'price_list_product', 'SKU')->withPivot('price');
    }

    public function contractLists()
    {
        return $this->hasMany(ContractList::class, 'SKU', 'SKU');
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('vat_percentage, count');
    }

    public function calculatePrice(?User $user) {
        if($user === null) {
            return $this->price;
        }

        $contractPrice = $this->contractLists()->where('user_id', $user->id)->first();
        if ($contractPrice) {
            return $contractPrice->price;
        }

        $userPriceList = $user->priceList;

        if($userPriceList) {
            $priceListPrice = $userPriceList->products()->where('price_list_product.SKU', $this->SKU)->withPivot('price')->first();
            if ($priceListPrice){
                return $priceListPrice->pivot->price;
            }
        }
        return $this->price;
    }

    public function calculateVatPercentage(Country $country)
    {
        $categories = $this->categories;

        $vatPercentages = $categories->map(function ($category) use ($country) {
            $vat = $category->vatPercentages()->where('country_id', $country->id)->first();
            return $vat ? $vat->pivot->vat_percentage : null;
        })->filter(function ($value) {
            return !is_null($value);
        });

        return $vatPercentages->isNotEmpty() ? $vatPercentages->min() : $country->default_vat;
    }
}
