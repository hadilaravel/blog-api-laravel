<?php

namespace Modules\Admin\Entities\Blog;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['author_id' , 'parent_id' , 'commentable' , 'body' , 'seen' ,'status'];

    public function user()
    {
        return $this->belongsTo(User::class , 'author_id');
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function parent()
    {
        return $this->belongsTo($this , 'parent_id');
    }

    public function answers ()
    {
        return $this->hasMany($this , 'parent_id');
    }

}
