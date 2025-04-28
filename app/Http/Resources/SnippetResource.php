<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SnippetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'code' => $this->code,
            'language' => $this->language,
            'created_at' => $this->created_at,
            'tags' => $this->tags->pluck('name'),
            'user' => $this->whenLoaded('user', function () {
                return new UserResource($this->user);
            }),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'likes' => LikeResource::collection($this->whenLoaded('likes')),
            'likes_count' => $this->when($this->likes_count !== null, $this->likes_count),
            'comments_count' => $this->when($this->comments_count !== null, $this->comments_count),
        ];
    }
}
