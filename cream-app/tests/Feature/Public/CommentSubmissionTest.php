<?php

namespace Tests\Feature\Public;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentSubmissionTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $o = []): array
    {
        return array_merge([
            'name' => 'Visitor',
            'email' => 'visitor@example.com',
            'body' => 'Great article, thank you.',
        ], $o);
    }

    public function test_a_valid_comment_is_stored_as_pending(): void
    {
        $a = Article::factory()->create();

        $this->post('/blog/'.$a->slug.'/comments', $this->payload())
            ->assertRedirect()
            ->assertSessionHas('comment_status', 'pending');

        $this->assertDatabaseHas('comments', [
            'article_id' => $a->id,
            'email' => 'visitor@example.com',
            'status' => 'pending',
        ]);
    }

    public function test_honeypot_submission_is_silently_dropped(): void
    {
        $a = Article::factory()->create();

        $this->post('/blog/'.$a->slug.'/comments', $this->payload(['website' => 'http://spam.example']))
            ->assertRedirect();

        $this->assertSame(0, Comment::count());
    }

    public function test_only_approved_comments_show_and_email_is_never_exposed(): void
    {
        $a = Article::factory()->create();
        $approved = Comment::factory()->approved()->create([
            'article_id' => $a->id, 'body' => 'APPROVED-BODY', 'email' => 'secret@example.com',
        ]);
        Comment::factory()->create(['article_id' => $a->id, 'body' => 'PENDING-BODY']);

        $this->get('/blog/'.$a->slug)
            ->assertOk()
            ->assertSee('APPROVED-BODY')
            ->assertDontSee('PENDING-BODY')
            ->assertDontSee('secret@example.com');
    }

    public function test_comments_are_rate_limited(): void
    {
        $a = Article::factory()->create();

        for ($i = 0; $i < 3; $i++) {
            $this->post('/blog/'.$a->slug.'/comments', $this->payload(['body' => "Comment {$i}"]))
                ->assertRedirect();
        }

        $this->post('/blog/'.$a->slug.'/comments', $this->payload(['body' => 'Too many']))
            ->assertStatus(429);
    }

    public function test_comments_closed_article_rejects_submission(): void
    {
        $a = Article::factory()->commentsClosed()->create();

        $this->post('/blog/'.$a->slug.'/comments', $this->payload())->assertForbidden();
    }
}
