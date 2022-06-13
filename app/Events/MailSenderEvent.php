<?php

namespace App\Events;

use App\Contracts\Mailable;

class MailSenderEvent extends Event
{
    public $instance;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Mailable $instance)
    {
        $this->instance = $instance;
    }
}
