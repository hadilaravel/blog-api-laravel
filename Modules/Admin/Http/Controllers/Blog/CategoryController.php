<?php

namespace Modules\Admin\Http\Controllers\Blog;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\Blog\Category;
use Modules\Admin\Http\Requests\Blog\CategoryRequest;
use Modules\Admin\Transformers\Blog\Category\CategoryCollection;

class CategoryController extends Controller
{

    public function index()
    {
        return \response()->json([
            'categories' => new CategoryCollection(Category::all())
        ]);
    }

    public function store(CategoryRequest $request)
    {
        $inputs = [
            'name' => $request->name,
            'parent_id'=> $request->parent_id,
            'status' => $request->status,
        ];
        $inputs['slug'] = persianSlug($request->name);
        $category = Category::query()->create($inputs);
        if($category) {
            return \response()->json([
                'msg' => 'عملیات با موفقیت انجام شد'
            ]);
        }else{
            return \response()->json([
                'msg' => 'عملیات انجام نشد !'
            ]);
        }
    }

    public function update(CategoryRequest $request , Category $category)
    {
        $inputs = [
            'name' => $request->name,
            'parent_id'=> $request->parent_id,
            'status' => $request->status,
        ];
        $inputs['slug'] = persianSlug($request->name);
        $category->update($inputs);
        return \response()->json([
            'msg' => 'عملیات با موفقیت انجام شد'
        ]);
    }

    public function delete(Category $category)
    {
        $category->delete();
        return \response()->json([
            'msg' => 'عملیات با موفقیت انجام شد'
        ]);
    }

    public function status(Category $category)
    {
        $category->status = $category->status == 0 ? 1 : 0;
        $category->save();
        return \response()->json([
            'msg' => 'عملیات با موفقیت انجام شد'
        ]);
    }

}
