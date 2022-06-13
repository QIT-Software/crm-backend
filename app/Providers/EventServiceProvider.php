<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\MailSenderEvent' => [
            'App\Listeners\MailSenderListener',
        ],
        'App\Events\DateWatcherEvent' => [
            'App\Listeners\DateWatcherListener',
        ],
        'App\Events\NotifierEvent' => [
            'App\Listeners\NotifierListener',
        ],
    ];
}
