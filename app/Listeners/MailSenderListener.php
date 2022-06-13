<?php

namespace App\Listeners;

use App\Events\MailSenderEvent;

class MailSenderListener
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
     * @param  MailSenderEvent  $event
     * @return void
     */
    public function handle(MailSenderEvent $event)
    {
        mail(
            'azhdar.shirinzade@azercosmos.az',
            $event->instance->subject(),
            $event->instance->body(),
            'From: ajdar.shirinzada@gmail.com'
        );
    }
}
