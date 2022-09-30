<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ForgotPassword;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPasswordMail;

class SendForgotPasswordNotification
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
    public function handle(ForgotPassword $event)
    {
        Mail::to($event->user)->send(new ForgotPasswordMail($event->user,$event->token));
    }
}
