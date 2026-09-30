<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'user_id'=>$this->user_id,
            'status'=>$this->status,
            'total'=>$this->total,
            'created_at'=>$this->created_at->format('Y-m-d'),
            'updated_at'=>$this->created_at->format('Y-m-d'),
            'order_product'=>OrderProductResource::collection($this->whenLoaded('products'))
        ];
    }
}
