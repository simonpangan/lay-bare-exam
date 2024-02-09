<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'sku'         => $this->sku,
            'description' => $this->description,
            'added_by'    => $this->added_by,
            'image'       => asset('storage/uploads/'.$this->image),

            'category_id'   => $this->category_id,
            'category_name' => $this->when($this->relationLoaded('category'),
                function () {
                    return $this->category->name;
                }
            )
        ];
    }
}
