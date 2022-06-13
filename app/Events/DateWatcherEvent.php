<?php

namespace App\Events;

use App\Contracts\Watchable;

class DateWatcherEvent extends Event
{
    public $instance;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Watchable $instance)
    {
        $this->instance = $instance;
    }
}
