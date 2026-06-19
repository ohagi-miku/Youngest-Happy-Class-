<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    private $post;

    private $category;

    public function __construct(Post $post, Category $category)
    {
        $this->post = $post;
        $this->category = $category;
    }

    #retrieve all categories needed
    public function create() {
        // ★修正：IDが6番（Uncategorized）以外のカテゴリーだけを取得
        $all_categories = $this->category->where('id', '!=', 6)->get(); 
        return view('users.posts.create')->with('all_categories', $all_categories);
        // send data to view(blade)
    }

    #insert all data from form to database
    public function store(Request $request) {
        $request->validate([
            'category'      => 'required|array|between:1,3',
            'description'    => 'required|min:1|max:1000',
            'image'         => 'required|mimes:jpeg,jpg,png,gif|max:1048'
        ]);

        #2. Save the post
        $this->post->user_id        = Auth::user()->id;
        $this->post->image          = 'data:image/' . $request->image->extension() .
                                        ';base64,' . base64_encode(file_get_contents($request->image));
        $this->post->description    = $request->description;
        $this->post->save();

        #. Save the categories to the category_post table
        foreach($request->category as $category_id) {
            $category_post[] = ['category_id' => $category_id];
            // category_post is an array that will hold the category ids choosen by the user
        }
        $this->post->categoryPost()->createMany($category_post);
        // we only provide category_id since post_id is automatically added because of "categoryPost()" relationship(Please check Post Model)
        // createMany() works life create(), excepts 2d array
        // createMany($category_post) insert the array category_post to database

        #4. Go back to homepage
        return redirect()->route('index');
    }


    
    #retrieve specific post($id)
    public function show($id) {
        $post = $this->post->findOrfail($id);
        return view('users.posts.show')->with('post', $post);
    }

    public function edit($id) {
        // get specific post to edit
        $post = $this->post->findOrFail($id);

        #If the AUTH user is NOT the owner of the post, redirect to homepage
        if(Auth::user()->id != $post->user->id) {
            return redirect()->route('index');
        }

        // ★修正：編集画面でも同じように6番（Uncategorized）以外のカテゴリーだけを取得
        $all_categories = $this->category->where('id', '!=', 6)->get();

        # Get all the selected category of IDs of the post. Save in an array
        $selected_categories = [];
        foreach($post->categoryPost as $category_post) {
            // get all the category IDs of the post
            // retrieves all rows from the category_post table that belong to the post
            $selected_categories[] = $category_post->category_id;
            // collect values from category_id colimn and store them in selected_categories array
        }

        return view('users.posts.edit')
                ->with('post', $post)
                ->with('all_categories', $all_categories)
                ->with('selected_categories', $selected_categories);
   }

   public function update(Request $request, $id) {
    $request->validate([
        'category'      => 'required|array|between:1,3',
        'description'   => 'required|min:1|max:1000',
        'image'         => 'nullable|mimes:jpeg,jpg,png,gif|max:1048'
    ]);

    $post = $this->post->findOrFail($id);

    if(Auth::user()->id != $post->user->id) {
        return redirect()->route('index');
    }

    if($request->hasFile('image')) {
        $post->image = 'data:image/' . $request->image->extension() .
                        ';base64,' . base64_encode(file_get_contents($request->image));
    }

    $post->description = $request->description;
    $post->save();


    $post->categoryPost()->delete();
    foreach($request->category as $category_id) {
        $category_post[] = ['category_id' => $category_id];
    }
    $post->categoryPost()->createMany($category_post);

    return redirect()->route('post.show', $id);
    }

    public function destroy($id) {
    $post = $this->post->findOrFail($id);

    if(Auth::user()->id != $post->user->id) {
        return redirect()->route('index');
    }

    // $post->categoryPost()->delete();

    $post->delete();

    return redirect()->route('index');
    }
}