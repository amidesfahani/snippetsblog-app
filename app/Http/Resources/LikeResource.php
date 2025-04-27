<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LikeResource extends JsonResource
{
	public function toArray($request)
	{
		return [
			'id' => $this->id,
			'user_id' => $this->user_id,
			'user' => new UserResource($this->whenLoaded('user')),
		];
	}
}
