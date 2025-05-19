<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail
{
    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        Mail::to($event->user->email)->send(new WelcomeEmail($event->user));
    }
}