<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_search_page_is_accessible(): void
    {
        $this->signIn();
        $response = $this->get('/blogs');
        $response->assertOk();
    }

    public function test_can_search_categories(): void
    {
        $this->withExceptionHandling();
        $this->signIn();
        Category::factory()->create(['name' => 'Laravel']);
        Category::factory()->create(['name' => 'Testing']);
        Category::factory()->create(['name' => 'Database']);

        $response = $this->get('/blogs?search=Laravel');

        $response->assertStatus(200);
        //$response->assertPathIs('/blogs');
        $response->assertSee('Laravel', true);
        $response->assertDontSeeText('Testing');
        $response->assertDontSeeText('Database');
    }

    public function test_can_search_blog_posts(): void
    {
        $user = $this->signIn();
        $this->withExceptionHandling();
        
        Blog::factory()->create(['blog_title' => 'Introduction to Laravel', 'blog_description' => 'Laravel is a PHP framework', 'created_by' => $user->id ]);
        Blog::factory()->create(['blog_title' => 'Testing in Laravel', 'blog_description' => 'Unit testing is important','created_by' => $user->id]);
        Blog::factory()->create(['blog_title' => 'Database Management', 'blog_description' => 'Database design and management', 'created_by' => $user->id]);

        $response = $this->get('/blogs?search=Laravel');

        $response->assertStatus(200);
        $response->assertSee('Introduction to Laravel');
        $response->assertDontSeeText('Testing in Laravel');
        $response->assertDontSeeText('Database Management');
    }
}
