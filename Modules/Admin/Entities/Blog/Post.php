<?php

namespace Modules\Admin\Entities\Blog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title' , 'slug' , 'body' , 'image' , 'status' , 'commentable' , 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class , 'category_id');
    }


}
