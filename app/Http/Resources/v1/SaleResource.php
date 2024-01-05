<?php

namespace App\Http\Resources\v1;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
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
            'client_id' => $this->client_id,
            'total_amount' => $this->total_amount,
            'profit' => $this->profit,
            'payment_id' => $this->payment_id,
            'isPaid' => $this->isPaid,
            'order_reference' => $this->order_reference,
            'created_at' => $this->created_at->format('d M H:i'),
            'client' => $this->whenLoaded('client'),
            'user' => $this->whenLoaded('user'),
            'movements' => MovementResource::collection($this->whenLoaded('movements')),
            'payment' => $this->whenLoaded('payment')
        ];
    }
}
