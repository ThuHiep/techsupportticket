<?php

namespace App\Mail;

use App\Models\Request as SupportRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DepartmentChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $supportRequest;

    /**
     * Create a new message instance.
     */
    public function __construct(SupportRequest $supportRequest)
    {
        $this->supportRequest = $supportRequest;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        // Lấy tên phòng ban mới:
        $newDepartmentName = optional($this->supportRequest->department)->department_name ?? 'N/A';
        $subject = 'Yêu cầu hỗ trợ đã được chuyển sang phòng ban ' . $newDepartmentName;

        return $this->from('no-reply@yourdomain.com', 'Phòng hỗ trợ kỹ thuật')
            ->subject($subject)
            ->view('emails.department_changed')
            ->with([
                'supportRequest' => $this->supportRequest,
                'newDepartmentName' => $newDepartmentName,
            ]);
    }
}
