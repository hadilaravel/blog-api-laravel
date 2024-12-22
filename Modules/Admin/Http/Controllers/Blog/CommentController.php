<?php

namespace Modules\Admin\Http\Controllers\Blog;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\Blog\Comment;
use Modules\Admin\Entities\Blog\Post;
use Modules\Admin\Http\Requests\Blog\CommentReqeust;

class CommentController extends Controller
{

    public function index()
    {
        $comments = Comment::query()->where('commentable_type', 'Modules\Admin\Entities\Blog\Post')->where('seen', 0)->get();
        return response()->json([
            'comments' => $comments
        ]);
    }

    public function delete(Comment $comment)
    {
        $comment->delete();
        return \response()->json([
            'msg' => 'عملیات با موفقیت انجام شد'
        ]);
    }

    public function status(Comment $comment){

        $comment->status = $comment->status == 0 ? 1 : 0;
        $comment->save();
        return \response()->json([
            'msg' => 'عملیات با موفقیت انجام شد'
        ]);
    }

    public function storeComment(CommentReqeust $request , Post $post)
    {
        $inputs = [
            'author_id' => auth()->id(),
            'body' => $request->body,
            'commentable_type' => Post::class,
            'commentable_id' => $post->id ,
        ];
       $comment =  Comment::query()->create($inputs);
       if($comment) {
           return \response()->json([
               'msg' => 'نظر شما پس از تایید در سایت نمایش داده میشود'
           ]);
       }else{
           return \response()->json([
               'msg' => 'ثبت نظر با خطا مواجه شد'
           ]);
       }
    }

}
