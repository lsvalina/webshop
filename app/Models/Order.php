<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone_number', 'address', 'city', 'country_id', 'user_id', 'subtotal', 'total'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product', 'order_id', 'SKU')
            ->withPivot('vat_percentage, quantity');
    }

    public function pricingModifiers()
    {
        return $this->belongsToMany(PricingModifier::class, 'order_pricing_modifier', 'order_id', 'pricing_modifier_id');
    }
}
