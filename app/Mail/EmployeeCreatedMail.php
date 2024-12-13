<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmployeeCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $employee;
    public $user_id;
    public $username;
    public $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($employee, $user_id, $username, $password)
    {
        $this->employee = $employee;
        $this->user_id = $user_id;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Thông tin tài khoản nhân viên')
            ->view('emails.employee_createdmail')

            ->with([
                'employee' => $this->employee,
                'user_id' => $this->user_id,
                'username' => $this->username,
                'password' => $this->password,
            ]);
    }
}
