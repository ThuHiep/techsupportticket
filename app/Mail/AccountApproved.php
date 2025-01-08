<?php

namespace App\Mail;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $password;

    public function __construct(Customer $customer, $password)
    {
        $this->customer = $customer;
        $this->password = $password;

    }

    public function build()
    {
        // Lấy thông tin username và password từ User
        $username = $this->customer->user->username; // Tên người dùng
        $password = $this->customer->user->password; // Mật khẩu (nếu bạn lưu mật khẩu dưới dạng plain text, điều này không an toàn)

        return $this->view('emails.account_approved')
            ->with([
                'customerName' => $this->customer->full_name,
                'username' => $username,
                'password' => $this-> password,
            ]);
    }
}
