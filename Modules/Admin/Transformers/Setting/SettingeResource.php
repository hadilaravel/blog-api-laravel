<?php

namespace Modules\Admin\Transformers\Setting;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
