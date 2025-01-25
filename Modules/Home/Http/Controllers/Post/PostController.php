<?php

namespace Modules\Home\Http\Controllers\Post;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\Blog\Category;
use Modules\Admin\Entities\Blog\Comment;
use Modules\Admin\Entities\Blog\Post;
use Modules\Admin\Transformers\Blog\Comment\CommentCollection;
use Modules\Admin\Transformers\Blog\Comment\CommentResource;
use Modules\Admin\Transformers\Blog\Post\PostCollection;
use Modules\Admin\Transformers\Blog\Post\PostResource;

class PostController extends Controller
{

    public function postActive(Request $request)
    {
        //        $user = auth()->user();
        $user = User::query()->where('id' , 2)->first();
        $postCount = Post::query()->where('status' , 1)->get();
        if(isset($request->search) || isset($request->take) || isset($request->categorySlug)){
            $search = $request->search;
            $take = $request->take ?? 15;
            $category = Category::query()->where('slug' , $request->categorySlug)->first();
            if(!empty($category))
            {
                $posts = Post::query()->where('status' , 1)->where('category_id' , $category->id)->paginate($take);
            }else{
                $posts = Post::query()->where('status' , 1)->where('title' , 'LIKE', '%' . $search . '%' )->paginate($take);
            }
        }else {
            $search ='';
            $posts = Post::query()->where('status' , 1)->paginate(15);
        }
        foreach ($posts as $post)
        {
            if($user->hasLiked($post))
            {
                $post->postLike = 1;
            }
        }
        return \response()->json([
            'posts' => new PostCollection($posts),
            'entityCount' => $postCount->count(),
            'PageCount' => (int) ceil($posts->total() / $posts->perPage())
        ]);
    }

    public function postComment(Post $post)
    {
        $comments = $post->activeComments();
        return response()->json([
           'comments' => new CommentCollection($comments)
        ] , 200);
    }

    public function postAddComment(Request $request , Post $post)
    {
        if($post->commentable === 1) {
            $inputs = [
                'author_id' => 5,
                'body' => $request->body,
                'commentable_type' => Post::class,
                'commentable_id' => $post->id,
            ];
            Comment::query()->create($inputs);
            return response()->json([
                'msg' => 'نظر شما با موفقیت ثبت شد'
            ]);
        }else{
            return response()->json([
                'msg' => 'نمیتوانید برای پست نظری ثبت کنید !'
            ] , 406);
        }
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
//        $user = auth()->user();
        $user = User::query()->where('id' , 2)->first();
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
