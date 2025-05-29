<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            'user' => [
                'id'    => $this->user->id,
            ],
            'item' => [
                'id'    => $this->item->id,
                'name'  => $this->item->name,
                'price' => $this->item->price,
            ],
            // 'quantity'     => $this->quantity,
            // 'total_price'  => $this->item->price * $this->quantity,
        ];
    }
}
