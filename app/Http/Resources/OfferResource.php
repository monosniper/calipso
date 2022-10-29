<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'days' => $this->days,
            'isSafe' => (bool) $this->isSafe,
            'price' => $this->price,
            'order' => new OrderResource($this->order),
            'user' =>  new UserResource($this->user),
            'created_at' => $this->created_at,
        ];
    }
}
