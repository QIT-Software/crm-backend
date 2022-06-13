<?php

namespace App\Contracts;

interface Watchable
{
    /**
     * Informs mail receiver related date has come.
     */
    public function informSubject();

    /**
     * Mail body
     */
    public function informBody();
}