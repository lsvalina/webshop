<?php

namespace App\Models;

use App\Traits\PricingModifier\Discounts;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingModifier extends Model
{
    use HasFactory;
    use Discounts;

    protected $fillable = ['name', 'type', 'value', 'operation', 'scope'];

    public function apply($price)
    {
        $modifiedPrice = $price;

        switch ($this->type) {
            case 'percentage':
                $modifierAmount = $price * $this->value;
                break;
            case 'fixed':
                $modifierAmount = $this->value;
                break;
        }

        switch ($this->operation) {
            case 'add':
                $modifiedPrice += $modifierAmount;
                break;
            case 'subtract':
                $modifiedPrice -= $modifierAmount;
                break;
        }

        return $modifiedPrice;
    }

    public function isApplicable($context)
    {
        if (empty($this->condition_function)) {
            return true;
        }

        $functionName = $this->condition_function;

        if (!method_exists($this, $functionName)) {
            return false;
        }

        return $this->{$functionName}($context);
    }
}
