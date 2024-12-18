<?php

namespace Modules\Admin\Entities\Blog;

use App\Traits\Accessor\CategoryAccessor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory , CategoryAccessor;

    protected $fillable = ['name' , 'slug' , 'status' , 'parent_id'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class , 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class , 'parent_id');
    }

}
