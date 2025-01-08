<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Register extends Mailable
{
    use Queueable, SerializesModels;

    public $customer; // Thay đổi từ user sang customer

    /**
     * Create a new message instance.
     *
     * @param $customer
     * @return void
     */
    public function __construct($customer)
    {
        $this->customer = $customer; // Gán khách hàng
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.register')
            ->subject('Đăng ký tài khoản!')
            ->with(['customer' => $this->customer]);
    }
}
