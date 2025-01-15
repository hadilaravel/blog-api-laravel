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
            'id' => $this->id,
            'name' => $this->name,
          'slug' => $this->slug,
          'parent_name' => $this->parent->name ?? null,
            'parent_id' => $this->parent_id ?? null,
            'status' => $this->status,
            'status_name' => $this->status_category,
            'created_at' => convertEnglishToPersian(jdate($this->created_at)->format('Y-m-d'))
        ];
    }
}
