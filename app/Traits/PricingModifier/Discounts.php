<?php

namespace App\Traits\PricingModifier;

trait Discounts
{
    protected function subtotalAboveThreshold($context): bool
    {
        if (!isset($context['subtotal'])) {
            return false;
        }

        return $context['subtotal'] > 10000;
    }

    protected function employeeDiscount($context): bool
    {
        if (!isset($context['email'])) {
            return false;
        }

        return str_contains($context['email'], '@mywebshop.com');
    }
}
