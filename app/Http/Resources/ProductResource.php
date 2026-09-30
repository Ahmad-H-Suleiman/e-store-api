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
            'id'=>$this->id,
            'seller_id'=>$this->seller_id,
            'name'=>$this->name,
            'bio'=>$this->bio,
            'image'=>$this->image,
            'price'=>$this->price,
            'is_available'=>$this->is_available,
            'seller'=>new SellerResource($this->whenLoaded('seller'))
        ];
    }
}
