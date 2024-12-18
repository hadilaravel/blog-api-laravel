<?php

namespace Modules\Admin\Transformers\Blog\Category;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
          'name' => $this->name,
          'slug' => $this->slug,
          'parent_id' => $this->parent->name ?? null,
          'status' => $this->status_category
        ];
    }
}
