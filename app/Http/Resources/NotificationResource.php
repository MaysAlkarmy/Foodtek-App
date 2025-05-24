<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['id'],
            'title' => @$this['data']['title'],
            'content' => @$this['data']['content'], // we put @ if the content will be null to dont throug error
            'created_at' => Carbon::parse($this['created_at'])->diffForHumans(),
            'read_at' => isset($this['read_at']) ? Carbon::parse($this['read_at'])->diffForHumans() : null,
        ];
    }
}
