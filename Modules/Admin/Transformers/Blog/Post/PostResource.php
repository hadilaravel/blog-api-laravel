<?php

namespace Modules\Admin\Transformers\Blog\Post;

use Illuminate\Http\Resources\Json\JsonResource;
use function asset;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "slug" => $this->slug,
            "body" => $this->body,
            "image" => asset($this->image),
            "status" => $this->status_post,
            "commentable" => $this->comment_post,
            "category" => $this->category->name,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
