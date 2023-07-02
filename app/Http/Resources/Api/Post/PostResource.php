<?php

namespace App\Http\Resources\Api\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'data'=>[
                'id' => $this->id,
                'title' => $this->title,
                'content' => $this->content,
                'publish' => $this->publish,
            ],
            'message' => 'Post Fetched Successfully!!',
            'status' => 200
        ] ;
    }
}
