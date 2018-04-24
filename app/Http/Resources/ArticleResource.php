<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
            'author_id' => $this->author_id,
            'author_name' => $this->user->name,
            'category_name' => $this->category->name,
            'title' => $this->title,
            'tag' => explode(',', $this->tag),
            'is_show_comment' => $this->is_show_comment ,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
