<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use App\Models\Category;
use App\Jobs\SendEmailJob;
use App\Constant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\Environment\Console;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            \Log::error('Error fetching blogs: ' . $e->getMessage());
            abort(500, Constant::SERVER_ERROR);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        try {
            $category = $this->getDynamicCategoryOptions();
            return view(Constant::CREATE, compact('category'));
        } catch (\Exception $exception) {
            \Log::error("Error in BlogController@create: " . $exception->getMessage());
            abort(500, Constant::SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            \Log::error('Error creating blog: ' . $e->getMessage());
            abort(500, Constant::SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {   
        try {

            $user = User::findorFail($blog->created_by)->pluck('name')->first();

            $cat = Blog::with('categories')->findOrFail($blog->id);

            return view(Constant::SHOW, compact('blog' , 'user' , 'cat'));
        } catch (\Exception $exception) {
            \Log::error("Error in BlogController Show: " . $exception->getMessage());
            abort(500, Constant::SERVER_ERROR);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        try {
            $blog = Blog::with('categories')->findOrFail($blog->id);
            $category = $this->getDynamicCategoryOptions();

            return view(Constant::EDIT, compact('blog','category'));

        } catch (\Exception $exception) {
            \Log::error("Error in BlogController@edit: " . $exception->getMessage());
            abort(500, Constant::SERVER_ERROR);
        }
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        try {
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
        } catch (\Exception $exception) {
            \Log::error("Error in Blog Update: " . $exception->getMessage());
            abort(500, Constant::SERVER_ERROR);
        }
    }

    /**
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        try {
            $blog->categories()->detach();

            $blog->delete();

            return redirect()->route(Constant::INDEX)->with('del_status', 'Blog Delete Successfully');
        } catch (\Exception $e) {
            \Log::error('Error deleting blog: ' . $e->getMessage());
            abort(500, Constant::SERVER_ERROR);
        }
    }

    private function getDynamicCategoryOptions()
    {
        return Category::all();
    }
}
