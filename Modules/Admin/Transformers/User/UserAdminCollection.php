<?php

namespace Modules\Admin\Transformers\User;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserAdminCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
