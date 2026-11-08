<?php

namespace Tests\Feature\Staff;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleCrudTest extends TestCase
{
    use RefreshDatabase;

    private function staff(): User
    {
        return User::factory()->staff()->create();
    }

    public function test_guest_cannot_reach_article_admin(): void
    {
        $this->get('/staff/articles')->assertRedirect('/staff/login');
    }

    public function test_screens_render(): void
    {
        $staff = $this->staff();
        $article = Article::factory()->create();

        $this->actingAs($staff)->get('/staff/articles')->assertOk();
        $this->actingAs($staff)->get('/staff/articles/create')->assertOk();
        $this->actingAs($staff)->get('/staff/articles/'.$article->slug.'/edit')->assertOk();
    }

    public function test_staff_can_create_and_publish_with_author_set(): void
    {
        $staff = $this->staff();

        $this->actingAs($staff)->post('/staff/articles', [
            'title' => 'My First Post',
            'status' => 'draft',
            'body' => '<p>Hello world</p>',
            'allow_comments' => '1',
            'publish' => '1',
        ])->assertRedirect();

        $article = Article::where('title', 'My First Post')->first();
        $this->assertNotNull($article);
        $this->assertSame('published', $article->status->value);
        $this->assertNotNull($article->published_at);
        $this->assertSame($staff->id, $article->author_id);
    }

    public function test_body_is_sanitised_on_save(): void
    {
        $this->actingAs($this->staff())->post('/staff/articles', [
            'title' => 'Dangerous Post',
            'status' => 'draft',
            'body' => '<p>safe</p><script>alert(1)</script><a href="javascript:evil()">x</a>',
        ]);

        $article = Article::where('title', 'Dangerous Post')->first();
        $this->assertStringNotContainsString('<script', $article->body);
        $this->assertStringNotContainsString('javascript:', $article->body);
        $this->assertStringContainsString('safe', $article->body);
    }

    public function test_delete_soft_deletes(): void
    {
        $article = Article::factory()->create();

        $this->actingAs($this->staff())->delete('/staff/articles/'.$article->slug)->assertRedirect();

        $this->assertSoftDeleted('articles', ['id' => $article->id]);
    }
}
