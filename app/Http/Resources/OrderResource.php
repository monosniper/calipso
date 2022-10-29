<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\Pure;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    #[Pure] public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'offers_count' => $this->offers_count,
            'category' => new CategoryResource($this->category),
            'user' => new UserResource($this->user),
            'price' => $this->price,
            'views' => $this->views,
            'status' => $this->status,
            'tags' => TagResource::collection($this->tags),
            'tag_ids' => $this->tags->pluck('id'),
            'isUrgent' => (bool) $this->isUrgent,
            'isSafe' => (bool) $this->isSafe,
            'days' => $this->days,
            'freelancer' => new UserResource($this->freelancer),
            'freelancer_id' => $this->freelancer_id,
            'category_id' => $this->category_id,
            'user_id' => $this->user_id,
            'order_files' => $this->getMedia('order_files')->toArray(),
            'created_at' => $this->created_at,
        ];
    }
}
