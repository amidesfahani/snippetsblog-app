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
            'user' => $this->whenLoaded('user', function () {
                return new UserResource($this->user);
            }),
        ];
    }
}
