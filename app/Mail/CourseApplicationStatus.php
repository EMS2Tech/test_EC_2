<?php

namespace App\Mail;

use App\Models\CourseApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CourseApplicationStatus extends Mailable
{
    use Queueable, SerializesModels;

    public $courseApplication;
    public $status;

    /**
     * Create a new message instance.
     *
     * @param CourseApplication $courseApplication
     * @param string $status
     * @return void
     */
    public function __construct(CourseApplication $courseApplication, string $status)
    {
        $this->courseApplication = $courseApplication;
        $this->status = $status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->status === 'Approved' ? 'Course Application Approved' : 'Course Application Rejected';
        return $this->subject($subject)
                    ->view('emails.course-application-status')
                    ->with([
                        'userName' => $this->courseApplication->user->name,
                        'programName' => $this->courseApplication->studyProgrammeName,
                        'courseName' => $this->courseApplication->courseName,
                        'status' => $this->status,
                        'reason' => $this->status === 'Rejected' ? $this->courseApplication->rejection_reason : null,
                        'subject' => $subject, // Add subject to the view data
                    ]);
    }
}