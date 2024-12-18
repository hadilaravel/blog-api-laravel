<?php

namespace App\Traits\Accessor;

trait CategoryAccessor
{

    public function getStatusCategoryAttribute()
    {
        if($this->status == 1)
        {
            $result = 'فعال';
        }else{
            $result = 'غیر فعال';
        }
        return $result;
    }

}
