<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FrontArticleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => FrontArticleResource::collection($this->collection),
            'links' => [
                'first' => $this->url(1),
                "prev" => $this->previousPageUrl(),
                "next" =>  $this->nextPageUrl(),
            ]
        ];
    }
}
