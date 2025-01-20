<?php

namespace Modules\Admin\Http\Controllers\Blog;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\Blog\Category;
use Modules\Admin\Http\Requests\Blog\CategoryRequest;
use Modules\Admin\Transformers\Blog\Category\CategoryCollection;
use Modules\Admin\Transformers\Blog\Category\CategoryResource;

class CategoryController extends Controller
{

    public function index(Request $request)
    {
        $catCount = Category::all();
        if(isset($request->search) || isset($request->take) ){
            $search = $request->search;
            $take = $request->take ?? 8;
            $categories = Category::query()->where('name' , 'LIKE', '%' . $search . '%' )->paginate($take);
        }else {
            $search ='';
            $categories = Category::query()->paginate(8);
        }
        return \response()->json([
            'categories' => new CategoryCollection($categories),
            'entityCount' => $catCount->count(),
            'PageCount' => (int) ceil($categories->total() / $categories->perPage())
        ]);
    }

    public function categoryActive()
    {
        $categories = Category::query()->where('status' , 1)->get();
        return \response()->json([
            'categories' => new CategoryCollection($categories)
        ]);
    }

    public function editActiveCategories(Category $category)
    {
        $categories = Category::query()->where('status' , 1)->get()->except($category->id);
        return \response()->json([
            'categories' => new CategoryCollection($categories)
        ]);
    }

    public function activeCategories()
    {
        $categories = Category::query()->where('status' , 1)->get();
        return \response()->json([
            'categories' => new CategoryCollection($categories)
        ]);
    }

    public function singleCategory(Category $category)
    {
        return response()->json([
            'category' => new CategoryResource($category)
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
            'msg' => 'دسته بندی با موفقیت ویرایش شد'
        ]);
    }

    public function delete(Category $category)
    {
        $category->delete();
        return \response()->json([
            'msg' => 'دسته بندی با موفقیت حذف شد'
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
