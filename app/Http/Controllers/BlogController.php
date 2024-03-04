<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use App\Models\Category;
use App\Jobs\SendEmailJob;
use App\Events\BlogCreated;
use App\Constant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Blog::with('categories');

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('blog_title', 'like', "%$searchTerm%")
                ->orWhere('blog_description', 'like', "%$searchTerm%")
                ->orWhereHas('categories', function ($categoryQuery) use ($searchTerm) {
                    $categoryQuery->where('name', 'like', "%$searchTerm%");
                });
                
        }

        $blogs = $query->latest()->paginate(3)->withQueryString();

        $categories = Category::all();

        return view(Constant::INDEX, compact('blogs', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = $this->getDynamicCategoryOptions();
        return view(Constant::CREATE, compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'blog_title' => 'required',
            'blog_description' => 'required',
        ]);

        $blog = Blog::create([
            'blog_title' => $request->blog_title,
            'blog_description' => $request->blog_description,
            'created_by' => Auth::user()->id,
        ]);

        if ($request->has('categories')) {
            $blog->categories()->attach($request->categories);
        
        }
        $userEmail = User::findorFail($blog->created_by)->pluck('email')->first();
        $data = [
                'email' => $userEmail,
                'blog_title' => $blog->blog_title,
                'blog_description' => $blog->blog_description
            ];

            dispatch(new SendEmailJob($data));
            
        return redirect()->route(Constant::INDEX)
                        ->with('status','Blogs Created Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {   
        $user = User::findorFail($blog->created_by)->pluck('name')->first();
        $cat = Blog::with('categories')->findOrFail($blog->id);
        return view(Constant::SHOW, compact('blog' , 'user' , 'cat'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        $blog = Blog::with('categories')->findOrFail($blog->id);
        $category = $this->getDynamicCategoryOptions();

        return view(Constant::EDIT, compact('blog','category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'blog_title' => 'required',
            'blog_description' => 'required',
        ]);

        $blog->blog_title = $request->blog_title;
        $blog->blog_description = $request->blog_description;

        if ($request->has('category')) {
            $blog->categories()->sync($request->category);
        }

        $blog->save();

        return redirect()->route(Constant::INDEX)->with('status', 'Blog Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        $blog->categories()->detach();

        $blog->delete();

        return redirect()->route(Constant::INDEX)->with('status', 'Blog Delete Successfully');
    }

    private function getDynamicCategoryOptions()
    {
        return Category::all();
    }
}
