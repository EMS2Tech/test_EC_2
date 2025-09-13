<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\CourseApplication;

class PaymentRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $emailMessage;

    public function __construct(CourseApplication $application, $message)
    {
        $this->application = $application;
        $this->emailMessage = $message;
    }

    public function build()
    {
        return $this->subject('Payment Request')
                    ->view('emails.payment-request')
                    ->with([
                        'application' => $this->application,
                        'emailMessage' => $this->emailMessage,
                        'course' => $this->application->course->course_name,
                        'batch' => $this->application->batch->batch_no,
                    ]);
    }
}