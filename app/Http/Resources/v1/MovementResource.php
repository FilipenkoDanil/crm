<?php

namespace App\Http\Resources\v1;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;

class MovementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request): array|\JsonSerializable|Arrayable
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'warehouse_id' => $this->warehouse_id,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'product' => ProductResource::make($this->whenLoaded('product')),
            'movementable_type' => class_basename($this->movementable_type),
            'movementable_id' => $this->movementable_id,
            'created_at' => $this->created_at->format('d-m-Y H:i'),
            'warehouse' => $this->whenLoaded('warehouse')
        ];
    }
}
