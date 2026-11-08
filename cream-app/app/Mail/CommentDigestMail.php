<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;

class CommentDigestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @param  Collection<int, \App\Models\Comment>  $comments
     */
    public function __construct(public Collection $comments) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->comments->count().' new comment(s) awaiting moderation — CREAM',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'mail.comment-digest');
    }
}
