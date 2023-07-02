<?php

namespace App\Http\Resources\Api\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PostCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function($data){
                return[
                    'id' => $data->id,
                    'title' => $data->title,
                    'publish' => $data->publish,
                    'created_at' => $data->created_at
                ];
            }),
            'message' => 'Posts fetched successfully.',
            'status' => 200
        ];
    }
}
