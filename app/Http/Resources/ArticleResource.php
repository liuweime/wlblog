<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     * @throws \Exception
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'author_id' => $this->author_id,
            'author_name' => $this->user->name,
            'category_name' => $this->category->name,
            'title' => $this->title,
            'content' => $this->when(empty($this->content)?false:true, $this->content),
            'tag' => explode(',', $this->tag),
            'is_show_comment' => $this->is_show_comment ,
            'comments' => $this->comment->count(),
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
