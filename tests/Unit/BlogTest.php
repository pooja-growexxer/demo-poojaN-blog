<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Blog;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BlogTest extends TestCase
{
    use RefreshDatabase;
    
    /*
    * @return void
    */
    public function test_post_index_and_create_page_is_not_displayed(): void
    {
        $user = '';
        $user ? true : false;
        $this->assertGuest();
        $this->get('/login');
    
    }

    /*
    * @return void
    */
    public function test_post_index_page_is_displayed(): void
    {
        $this->signIn();

        $response = $this->get('/blogs');
        $response->assertOk();
        $response->assertStatus(200);
        $response->assertViewIs('blogs.index');
        $response->assertViewHas('blogs');
    }

        /*
    * @return void
    */
    public function test_blog_create_view_is_displayed(): void
    {
        $this->signIn();
        $response = $this->get('/blogs/create');
        $response->assertStatus(200);
        $response->assertViewIs('blogs.create');
    }

    /*
    * @return void
    */
    public function test_blog_can_create_by_login_user(): void
    {
        $user = $this->signIn();

        $response = $this->post('/blogs', [
            'blog_title'  => 'Test1 title',
            'blog_description' => 'Test1 blog description',
            'created_by' => $user->id
        ]);
        $this->assertAuthenticated();

        $response->assertRedirect(RouteServiceProvider::HOME);

        $this->assertDatabaseHas('blogs', [
            'blog_title' => 'Test1 title',
            'blog_description'  => 'Test1 blog description',
        ]);
        $this->assertDatabaseCount('blogs', 1);
    }

    /*
    * @return void
    */
    public function test_edit_blog_view_option_visible_to_login_user(): void
    {
        $user = $this->signIn();

        $blog = Blog::create([
            'blog_title'  => 'Test2 title',
            'blog_description' => 'Test2 blog description',
            'created_by' => $user->id
        ]);

        $response = $this->get('/blogs/' . $blog->id . '/edit');

        $response->assertStatus(200);

        $response->assertViewIs('blogs.edit');

        $response->assertViewHas('blog');
    }

    /*
    * @return void
    */
    public function test_login_user_can_able_to_update_blog(): void
    {

       // $this->refreshApplication();
        $this->withoutExceptionHandling();
        $user = $this->signIn();
        
        $post = Blog::create([
            'blog_title'  => 'Test n title',
            'blog_description' => 'Test2 n blog description',
            'created_by' => $user->id
        ]);

        $response = $this->patch('/blogs/' . $post->id, [
            'blog_title'  => 'Test updated title',
            'blog_description' => 'Test2 updated description',
            'created_by' => $user->id
        ]);

        $response->assertRedirect(RouteServiceProvider::HOME);

        $this->assertDatabaseHas('blogs', [
            'blog_title' => 'Test updated title',
        ]);
    }

    /*
    * @return void
    */
    public function test_login_user_able_to_see_show_view(): void
    {
        $user = $this->signIn();
        $blog = Blog::create([
            'blog_title'  => 'Test show ',
            'blog_description' => 'Test show',
            'created_by' => $user->id
        ]);
        $response = $this->get('/blogs/' . $blog->id);

        $response->assertStatus(200);

        $response->assertViewIs('blogs.show');

        $response->assertViewHas('blog');
    }

    /*
    * @return void
    */
    public function test_loginuser_able_to_delete_blog(): void
    {
        $this->withoutExceptionHandling();

        $user = $this->signIn();

        $blog = Blog::create([
            'blog_title'  => 'Test show ',
            'blog_description' => 'Test show11',
            'created_by' => $user->id
        ]);
        $response = $this->delete('/blogs/' . $blog->id);
        $response
        ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('blogs', [
            'blog_title'  => 'Test show 22',
        ]);
    }
}
