<?php

namespace Modules\Admin\Transformers\Blog\Comment;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
        'id' => $this->id,
        'author_id' => $this->author_id,
            'author_name' => $this->user->username,
            'parent_id' => $this->parent_id,
        'parent_body' => $this->parent->body ?? null,
        'status' => $this->status,
            'body' => $this->body,
            'seen' => $this->seen,
        'created_at' => convertEnglishToPersian(jdate($this->created_at)->format('Y-m-d'))
    ];
    }
}
