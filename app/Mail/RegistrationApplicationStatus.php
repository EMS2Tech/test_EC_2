<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationApplicationStatus extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $status;

    /**
     * Create a new message instance.
     *
     * @param Application $application
     * @param string $status
     * @return void
     */
    public function __construct(Application $application, string $status)
    {
        $this->application = $application;
        $this->status = $status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->status === 'Approved' ? 'Registration Application Approved' : 'Registration Application Rejected';
        return $this->subject($subject)
                    ->view('emails.registration-application-status')
                    ->with([
                        'userName' => $this->application->user->name,
                        'fullName' => $this->application->full_name,
                        'status' => $this->status,
                        'reason' => $this->status === 'Rejected' ? $this->application->rejection_reason : null,
                        'subject' => $subject, // Pass subject to view
                    ]);
    }
}