<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id' => $this['id'],
            'name' => @$this['name'],
            'description' => @$this['description'], // we put @ if the content will be null to dont throug error
            'price' => @$this['price'],
            'image' =>@$this['image'],
            'review_count'  => $this->reviews->count(),
             ];
        }
}
