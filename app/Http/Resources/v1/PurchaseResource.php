<?php

namespace App\Http\Resources\v1;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
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
            'supplier_id' => $this->supplier_id,
            'user_id' => $this->user_id,
            'total_amount' => $this->total_amount,
            'isApproved' => $this->isApproved,
            'movements' => $this->whenLoaded('movements')
        ];
    }
}
