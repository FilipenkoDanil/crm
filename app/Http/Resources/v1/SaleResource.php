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
            'created_at' => $this->created_at->format('d-m-y'),
            'movements'=> MovementResource::collection($this->whenLoaded('movements'))
        ];
    }
}
