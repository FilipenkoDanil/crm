<?php

namespace App\Http\Resources\v1;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
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
            'title' => $this->title,
            'barcode' => $this->barcode,
            'code' => $this->code,
            'image' => $this->image ? Storage::url($this->image) : null,
            'category_id' => $this->category_id,
            'purchase_price' => $this->purchase_price,
            'selling_price' => $this->selling_price,
            'category' => $this->whenLoaded('category'),
            'pivot' => $this->whenLoaded('pivot')
        ];
    }
}
