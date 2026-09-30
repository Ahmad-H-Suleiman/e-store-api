<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order_id'=>$this->order_id,
            'product_id'=>$this->product_id,
            'price'=>$this->price,
            'quantity'=>$this->quantity,
            'subtotal'=>$this->subtotal,
            'product'=>new SellerResource($this->whenLoaded('product'))
        ];
    }
}
