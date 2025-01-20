<?php

namespace Modules\Admin\Http\Controllers\Blog;

use App\Service\ShareService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\Blog\Post;
use Modules\Admin\Http\Requests\Blog\PostRequest;
use Modules\Admin\Transformers\Blog\Post\PostCollection;
use Modules\Admin\Transformers\Blog\Post\PostResource;

class PostController extends Controller
{

    public function index(Request $request)
    {
        $postCount = Post::all();
        if(isset($request->search) || isset($request->take) ){
            $search = $request->search;
            $take = $request->take ?? 8;
            $posts = Post::query()->where('title' , 'LIKE', '%' . $search . '%' )->paginate($take);
        }else {
            $search ='';
            $posts = Post::query()->paginate(8);
        }
        return \response()->json([
           'posts' => new PostCollection($posts),
            'entityCount' => $postCount->count(),
            'PageCount' => (int) ceil($posts->total() / $posts->perPage())
        ]);
    }

    public function singlePost(Post $post)
    {
        return response()->json([
           'post' => new PostResource($post)
        ]);
    }

    public function store(PostRequest $request)
    {
        $inputs = [
            'title' => $request->title,
            'body' => $request->body,
            'category_id'=> $request->category_id,
            'status' => $request->status,
            'commentable' => $request->commentable,
        ];
        $inputs['slug'] = persianSlug($request->title);
        if($request->hasFile('image')) {
            $imageName = ShareService::uploadFilePublic($request->file('image') ,'image/post');
            $inputs['image'] = $imageName;
        }
        $post = Post::query()->create($inputs);
        if($post) {
            return \response()->json([
                'msg' => 'عملیات با موفقیت انجام شد'
            ]);
        }else{
            return \response()->json([
                'msg' => 'عملیات انجام نشد !'
            ]);
        }
    }

    public function update(PostRequest $request , Post $post)
    {
        $inputs = [
            'title' => $request->title,
            'body' => $request->body,
            'category_id'=> $request->category_id,
            'status' => $request->status,
            'commentable' => $request->commentable,
        ];
        $inputs['slug'] = persianSlug($request->title);
        if($request->hasFile('image')) {
            ShareService::deleteFilePublic($post->image);
            $imageName = ShareService::uploadFilePublic($request->file('image') ,'image/post');
            $inputs['image'] = $imageName;
        }
        $post->update($inputs);
            return \response()->json([
                'msg' => 'عملیات با موفقیت انجام شد'
            ]);
    }

    public function delete(Post $post)
    {
        ShareService::deleteFilePublic($post->image);
        $post->delete();
        return \response()->json([
            'msg' => 'عملیات با موفقیت انجام شد'
        ]);
    }


    public function status(Post $post)
    {
        $post->status = $post->status == 0 ? 1 : 0;
        $post->save();
        return \response()->json([
            'msg' => 'عملیات با موفقیت انجام شد'
        ]);
    }

}
