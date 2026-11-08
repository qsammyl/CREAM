<?php

namespace Tests\Feature\Staff;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentModerationTest extends TestCase
{
    use RefreshDatabase;

    private function staff(): User
    {
        return User::factory()->staff()->create();
    }

    public function test_guest_cannot_reach_moderation(): void
    {
        $this->get('/staff/comments')->assertRedirect('/staff/login');
    }

    public function test_moderation_screen_renders(): void
    {
        Comment::factory()->create();

        $this->actingAs($this->staff())->get('/staff/comments')->assertOk();
    }

    public function test_staff_can_approve_and_it_becomes_public(): void
    {
        $article = Article::factory()->create();
        $comment = Comment::factory()->create(['article_id' => $article->id, 'body' => 'NICE-ONE']);

        $this->actingAs($this->staff())->post('/staff/comments/'.$comment->id.'/approve')->assertRedirect();

        $this->assertDatabaseHas('comments', ['id' => $comment->id, 'status' => 'approved']);
        $this->get('/blog/'.$article->slug)->assertSee('NICE-ONE');
    }

    public function test_staff_can_mark_spam_and_delete(): void
    {
        $spammy = Comment::factory()->create();
        $this->actingAs($this->staff())->post('/staff/comments/'.$spammy->id.'/spam')->assertRedirect();
        $this->assertDatabaseHas('comments', ['id' => $spammy->id, 'status' => 'spam']);

        $doomed = Comment::factory()->create();
        $this->actingAs($this->staff())->delete('/staff/comments/'.$doomed->id)->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $doomed->id]);
    }

    public function test_bulk_approve(): void
    {
        $ids = Comment::factory()->count(3)->create()->pluck('id')->all();

        $this->actingAs($this->staff())
            ->post('/staff/comments/bulk', ['action' => 'approve', 'ids' => $ids])
            ->assertRedirect();

        $this->assertSame(3, Comment::where('status', 'approved')->count());
    }
}
