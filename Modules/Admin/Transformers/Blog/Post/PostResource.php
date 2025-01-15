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
            "status" => $this->status,
            "commentable" => $this->commentable,
            "category" => $this->category->name,
            "category_id" => $this->category_id,
            'created_at' => convertEnglishToPersian(jdate($this->created_at)->format('Y-m-d'))
        ];
    }
}
