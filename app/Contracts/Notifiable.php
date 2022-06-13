<?php

namespace App\Contracts;

interface Notifiable
{
    /**
    * Notification message
    */
    public function message();

    /**
    * Created by
    */
    public function owners();
}