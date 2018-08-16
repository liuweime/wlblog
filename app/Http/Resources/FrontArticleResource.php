<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FrontArticleResource extends JsonResource
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
            'author' => $this->user->name,
            'category' => $this->category->name,
            'excerpt' => $this->when(empty($this->excerpt) ? false : true, $this->excerpt),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
