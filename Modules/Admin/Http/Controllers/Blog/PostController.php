<?php

namespace Modules\Admin\Http\Controllers\Blog;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\Blog\Post;

class PostController extends Controller
{

    public function index()
    {
        return \response()->json([
           'posts' => Post::all()
        ]);
    }

    public function store()
    {

    }

}
