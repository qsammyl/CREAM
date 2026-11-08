<?php

namespace App\Http\Controllers\Staff;

use App\Enums\CommentStatus;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function index(Request $request): View
    {
        $tab = in_array($request->input('tab'), ['pending', 'approved', 'spam'], true)
            ? $request->input('tab') : 'pending';

        $comments = Comment::query()->with('article')
            ->where('status', $tab)
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $counts = [
            'pending' => Comment::where('status', 'pending')->count(),
            'approved' => Comment::where('status', 'approved')->count(),
            'spam' => Comment::where('status', 'spam')->count(),
        ];

        return view('staff.comments.index', compact('comments', 'tab', 'counts'));
    }

    public function approve(Comment $comment): RedirectResponse
    {
        $this->setStatus($comment, CommentStatus::Approved, 'approve');

        return back()->with('flash', 'Comment approved.');
    }

    public function spam(Comment $comment): RedirectResponse
    {
        $this->setStatus($comment, CommentStatus::Spam, 'spam');

        return back()->with('flash', 'Comment marked as spam.');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        ActivityLogger::log('delete', $comment);
        $comment->delete();

        return back()->with('flash', 'Comment deleted.');
    }

    public function bulk(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', 'in:approve,spam,delete'],
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        $comments = Comment::whereIn('id', $data['ids'])->get();
        foreach ($comments as $comment) {
            match ($data['action']) {
                'approve' => $this->setStatus($comment, CommentStatus::Approved, 'approve'),
                'spam' => $this->setStatus($comment, CommentStatus::Spam, 'spam'),
                'delete' => tap($comment, fn ($c) => ActivityLogger::log('delete', $c))->delete(),
            };
        }

        return back()->with('flash', count($comments).' comment(s) updated.');
    }

    protected function setStatus(Comment $comment, CommentStatus $status, string $action): void
    {
        $comment->update([
            'status' => $status,
            'moderated_by' => auth()->id(),
            'moderated_at' => now(),
        ]);
        ActivityLogger::log($action, $comment);
    }
}
