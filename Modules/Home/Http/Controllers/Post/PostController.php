<?php

namespace Modules\Home\Http\Controllers\Post;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\Blog\Post;
use Modules\Admin\Transformers\Blog\Post\PostCollection;
use Modules\Admin\Transformers\Blog\Post\PostResource;

class PostController extends Controller
{

    public function postActive()
    {
        $posts = Post::query()->where('status' , 1)->get();
        return response()->json([
            'posts' => new PostCollection($posts)
        ]);
    }

    public function postDetail(Post $post)
    {
        views($post)->unique()->record();
        $views = views($post)->count();
        $likes =  $post->likers()->count();
        return response()->json([
            'post' => new PostResource($post),
            'countLikes' => $likes,
            'countViews' => $views
        ]);
    }

    public function likePost(Post $post)
    {
        $user = auth()->user();
        $user->toggleLike($post);
        if($user->hasLiked($post)) {
            return response()->json([
                'msg' => 'پست با موفقیت لایک شد',
                'res' => 1
            ]);
        }else{
            return response()->json([
                'msg' => 'پست با موفقیت آن لایک شد',
                'res' => 0
            ]);
        }
    }

}
