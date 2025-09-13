<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentStatusUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;
    public $status;

    /**
     * Create a new message instance.
     *
     * @param Payment $payment
     * @param string $status
     * @return void
     */
    public function __construct(Payment $payment, string $status)
    {
        $this->payment = $payment;
        $this->status = $status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->status === 'Approved' ? 'Payment Approved' : 'Payment Rejected';
        return $this->subject($subject)
                    ->view('emails.payment-status-update')
                    ->with([
                        'userName' => $this->payment->user->name,
                        'status' => $this->status,
                        'reason' => $this->status === 'Rejected' ? $this->payment->rejection_reason : null,
                        'subject' => $subject, // Pass subject to view
                    ]);
    }
}