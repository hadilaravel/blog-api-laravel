<?php

namespace App\Traits\Accessor;

trait PostAccessor
{

    public function getStatusPostAttribute()
    {
        if($this->status == 1)
        {
            $result = 'فعال';
        }else{
            $result = 'غیر فعال';
        }
        return $result;
    }

    public function getCommentPostAttribute()
    {
        if($this->commentable == 1)
        {
            $result = 'فعال';
        }else{
            $result = 'غیر فعال';
        }
        return $result;
    }

}
