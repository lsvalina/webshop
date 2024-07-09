<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'SKU' => $this->SKU,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->calculated_price / 100,
            'categories' => CategoryResource::collection($this->categories)
        ];
    }
}
