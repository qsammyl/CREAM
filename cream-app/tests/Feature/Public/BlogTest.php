<?php

namespace Tests\Feature\Public;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_index_lists_published_not_draft(): void
    {
        $live = Article::factory()->create(['title' => 'Live Post']);
        $draft = Article::factory()->draft()->create(['title' => 'Draft Post']);

        $this->get('/blog')
            ->assertOk()
            ->assertSee('Live Post')
            ->assertDontSee('Draft Post');
    }

    public function test_published_article_renders(): void
    {
        $a = Article::factory()->create();

        $this->get('/blog/'.$a->slug)->assertOk()->assertSee($a->title);
    }

    public function test_draft_article_is_not_public(): void
    {
        $a = Article::factory()->draft()->create();

        $this->get('/blog/'.$a->slug)->assertNotFound();
    }

    public function test_legacy_article_resolves_at_original_url(): void
    {
        Article::factory()->create([
            'title' => 'Legacy Post',
            'legacy_path' => 'blog-rental-experiences-tears',
        ]);

        $this->get('/blog-rental-experiences-tears')->assertOk()->assertSee('Legacy Post');
    }
}
