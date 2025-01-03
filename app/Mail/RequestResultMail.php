<?php

namespace App\Mail;

use App\Models\Request as SupportRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestResultMail extends Mailable
{
    use Queueable, SerializesModels;

    public $supportRequest;

    /**
     * Tạo instance của RequestResultMail.
     */
    public function __construct(SupportRequest $supportRequest)
    {
        $this->supportRequest = $supportRequest;
    }

    /**
     * Xây dựng email.
     */
    public function build()
    {
        // Lấy thêm dữ liệu liên quan
        $departmentName = optional($this->supportRequest->department)->department_name ?? 'Chưa xác định';
        $requestTypeName = optional($this->supportRequest->requestType)->request_type_name ?? 'Chưa xác định';
        $subject = 'Trạng thái xử lý của yêu cầu: ' . $this->supportRequest->subject;

        return $this->from('no-reply@yourdomain.com', 'TechSupportTicket System')
            ->subject($subject)
            ->view('emails.request_result')  // Blade template của email
            ->with([
                'supportRequest'  => $this->supportRequest,
                'departmentName'  => $departmentName,
                'requestTypeName' => $requestTypeName,
            ]);
    }
}
