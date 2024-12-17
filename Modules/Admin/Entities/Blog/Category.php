<?php

namespace Modules\Admin\Entities\Blog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name' , 'slug' , 'status' , 'parent_id'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }


}
