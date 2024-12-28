<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FaqFeedback extends Mailable
{
    use Queueable, SerializesModels;

    public $question;
    public $answer;

    public function __construct($question, $answer)
    {
        $this->question = $question;
        $this->answer = $answer;
    }

    public function build()
    {
        return $this->subject('Gửi câu hỏi của bạn')
            ->view('emails.faq_feedback')
            ->with([
                'question' => $this->question,
                'answer' => $this->answer,
            ]);
    }
}
