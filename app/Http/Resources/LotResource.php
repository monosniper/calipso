<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'isPremium' => (bool) $this->isPremium,
            'views' => $this->views,
            'properties' => $this->properties,
            'tags' => TagResource::collection($this->tags),
            'tag_ids' => $this->tags->pluck('id'),
            'category' => new CategoryResource($this->category),
            'user' => new UserResource($this->user),
            'category_id' => $this->category_id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'archive' => $this->getFirstMedia('archive'),
            'images' => $this->getMedia('images')->toArray(),
        ];
    }
}
