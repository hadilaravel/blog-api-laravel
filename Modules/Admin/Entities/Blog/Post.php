<?php

namespace Modules\Admin\Entities\Blog;

use App\Traits\Accessor\PostAccessor;
use CyrildeWit\EloquentViewable\Support\Period;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Overtrue\LaravelLike\Traits\Likeable;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;

class Post extends Model implements Viewable
{
    use HasFactory , PostAccessor , Likeable , InteractsWithViews;

    protected $fillable = ['title' , 'slug' , 'body' , 'image' , 'status' , 'commentable' , 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class , 'category_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class , 'commentable');
    }

}
