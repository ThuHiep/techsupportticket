<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerCreated extends Mailable
{
    use SerializesModels;

    public $username;
    public $password;
    public $email;

    public function __construct($username, $password, $email)
    {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
    }

    public function build()
    {
        return $this->view('emails.customer_created')
            ->with([
                'username' => $this->username,
                'password' => $this->password,
            ])
            ->subject('Tài khoản và mật khẩu tạm thời');
    }
}
