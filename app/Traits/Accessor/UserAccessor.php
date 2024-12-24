<?php

namespace App\Traits\Accessor;

trait UserAccessor
{

    public function getTypeUserAttribute()
    {
        if($this->user_type == 1)
        {
            $result = 'ادمین';
        }else{
            $result = 'کاربر ساده';
        }
        return $result;
    }

    public function getActivationUserAttribute()
    {
        if($this->activation == 1)
        {
            $result = 'فعال';
        }else{
            $result = 'غیر فعال';
        }
        return $result;
    }

}
