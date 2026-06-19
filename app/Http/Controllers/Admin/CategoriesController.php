<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\CategoryPost;

class CategoriesController extends Controller
{
    public function index()
    {
        $all_categories = Category::latest()->paginate(10);
        
        return view('admin.categories.index', compact('all_categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:50|unique:categories,name,' . $id
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->save();

        return redirect()->back();
    }

    public function destroy($id)
    {
        $related_post_ids = CategoryPost::where('category_id', $id)
                                        ->pluck('post_id')
                                        ->toArray();

        CategoryPost::where('category_id', $id)->delete();

        Category::destroy($id);

        foreach ($related_post_ids as $post_id) {

            $category_count = CategoryPost::where('post_id', $post_id)->count();

            if ($category_count === 0) {
                CategoryPost::create([
                    'category_id' => 6, 
                    'post_id'     => $post_id
                ]);
            }
        }

        return redirect()->back();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50|unique:categories,name' // 同じ名前の重複を防ぐバリデーション
        ]);

        $category = new Category;
        $category->name = $request->name;
        $category->save();

        return redirect()->back();
    }
}