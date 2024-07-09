<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'default_vat'];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'country_category')
            ->withPivot('vat_percentage');
    }
}
