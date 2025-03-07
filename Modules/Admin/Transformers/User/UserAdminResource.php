<?php

namespace Modules\Admin\Transformers\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserAdminResource extends JsonResource
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
        "username"=> $this->username,
        "name" => $this->name,
        "mobile"=> $this->mobile,
        "email"=> $this->email,
        "user_type" => $this->type_user,
            "activation" => $this->activation,
            "activationUser" => $this->activation_user,
            "profile" => $this->profile ? asset($this->profile) : 1,
            'created_at' => convertEnglishToPersian(jdate($this->created_at)->format('Y-m-d'))
        ];
    }
}
