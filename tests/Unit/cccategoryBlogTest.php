<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Blog;
use App\Models\Category;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class cccategoryBlogTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function categoryDropdown_in_createview_is_displayed(): void
    {
        $this->signIn();
        $response = $this->get('/blogs/create');
        $response->assertStatus(200);
        $response->assertViewIs('blogs.create');

        $category = Category::all();
        $category->each(function (Category $category) use($response) {
            $response->assertSee($category->name);
        });

        $response->assertSeeInOrder($category->map(function (Category $category) {
            return "$category->name";
        })->all());
    }



    /** @test */
    public function select_category_option_contains_expected_values(): void
    {
        $this->signIn();
        $response = $this->get('/blogs/create');

        $response->assertStatus(200);

        $category = Category::all()->toArray();
        foreach ($category as $option) {
            $response->assertSee($option);
            $response->assertSee($option['name']);
            $response->assertSee($option['id']);

        }
    }

    /** @test */
    public function a_user_can_create_a_blog_with_categories(): void
    {
        $user = $this->signIn();
        $categories = Category::factory(3)->create();
        
        $response = $this->post('/blogs', [
            'blog_title'  => 'blg111',
            'blog_description' => 'description111',
            'created_by' => $user->id,
            'categories' => $categories->pluck('id')->toArray(),
        ]);

        $response->assertRedirect('/blogs');
        $this->assertDatabaseCount('blogs', 1);
        $this->assertDatabaseHas('blogs', [
            'blog_title'  => 'blg111',
            'blog_description' => 'description111',
        ]);
        $this->assertEquals(3, Blog::first()->categories->count());
        $this->assertCount(3, Blog::first()->categories);
    }


    /** @test */
    public function a_blog_can_be_updated_with_categories(): void
    {
       
        $blog = Blog::factory()->create();
        $categories = Category::factory()->count(3)->create();
        $postData = [
            'blog_title'  => 'blg222',
            'blog_description' => 'description222',
            'categories' => $categories->pluck('id')->toArray(),
        ];
        $this->signIn();
        $response = $this->patch('/blogs/' . $blog->id, $postData);
        
        $response->assertRedirect(RouteServiceProvider::HOME);

        $blog = $blog->fresh();
        $this->assertDatabaseHas('blogs', ['blog_title' => 'blg222']);
    }

    /** @test */
    public function test_blog_can_update_a_with_categories_using_attach(): void
    {
        $user = $this->signIn();
        $blog =  Blog::factory()->create();

        $categories = Category::factory(3)->create();
        $blog->categories()->attach($categories);

        $blogData = [
            'blog_title'  => 'blg111',
            'blog_description' => 'description111',
            'created_by' => $user->id,
            'categories' => $categories->pluck('id')->toArray(),
        ];

        // Act
        $this->put("/blogs/{$blog->id}", $blogData);

        // Assert
        //$response->assertStatus(200); // OK
        $this->assertDatabaseHas('blogs', ['blog_title' => 'blg111']);
    
        // $this->assertDatabaseHas('blog_category', ['blog_id' => 1, 'category_id' => $categories->first()->id]);



        $this->assertEquals(3, $blog->fresh()->categories->count());
    }
    
    /** @test */
    public function a_blog_can_be_deleted_with_categories(): void
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $blog =  Blog::factory()->create();

        $response = $this->delete('/blogs/' . $blog->id);
        $response
        ->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('blogs', ['id' => $blog->id]);
    }

    /** @test */
    public function ensure_pivot_records_deleted_with_categories(): void
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $blog =  Blog::factory()->create();
        $categories = Category::factory()->count(2)->create();
        $blog->categories()->attach($categories);

        $this->delete('/blogs/' . $blog->id);
  
        $this->assertDatabaseMissing('blogs', ['id' => $blog->id]);
        
        // Ensure pivot records are deleted
        foreach ($categories as $category) {
            $this->assertDatabaseMissing('blog_category', [
                'blog_id' => $blog->id,
                'category_id' => $category->id,
            ]);
        }
    }

    /** @test */
    public function a_user_can_view_a_blog_with_categories()
    {
        $this->signIn();

        $post = Blog::factory()->create();
        $category = Category::factory(3)->create();
        $post->categories()->attach($category);

        $response = $this->get("/blogs/{$post->id}");

        $response->assertStatus(200);

        // $this->assertDatabaseHas('blog_category', [
        //     'blog_id' => $post->id,
        //     'category_id' => $category->id,
        // ]);
        
    }
    public function test_can_detach_category_from_post()
    {
        $this->signIn();

        $category = Category::factory()->create();
        $post = Blog::factory()->create();
        $post->categories()->attach($category);

        $response = $this->delete("/blog/{$post->id}");
        $post->categories()->detach();
      //  $response->assertStatus(200);

        $this->assertDatabaseMissing('blog_category', [
            'blog_id' => $post->id,
            'category_id' => $category->id,
        ]);
    }
}
