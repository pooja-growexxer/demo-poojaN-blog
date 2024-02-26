<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // $blogs = Blog::latest()->paginate(4);
        $blogs = Blog::with(['categories'])->latest()->paginate(4);

        return view('blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$category = Category::all();
        $category = $this->getDynamicCategoryOptions();
        return view('blogs.create', compact('category'));
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
        return redirect()->route('blogs.index')
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
        return view('blogs.show', compact('blog' , 'user'));
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
       // $category = Category::all();
       $category = $this->getDynamicCategoryOptions();
        return view('blogs.edit',compact('blog','category'));
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

        return redirect()->route('blogs.index')->with('status', 'Blog Updated Successfully');
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

        return redirect()->route('blogs.index')->with('status', 'Blog Delete Successfully');
    }

    public function getDynamicCategoryOptions()
    {
        $category = Category::all();
        return $category;
    }
}
