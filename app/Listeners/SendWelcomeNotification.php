<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\UserRegistered;
use Illuminate\Support\Facades\Mail;

use App\Mail\WelcomeMail;

class SendWelcomeNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {

        // Mail::to($event->user)->send(new ForgotPasswordMail($event->user,$event->token));
       
        Mail::to($event->user)->send(new  welcomeMail($event->user));
    }
}
