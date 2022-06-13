<?php

namespace App\Contracts;

interface Mailable
{
    /**
    * @return string
    */
    public function subject();

    /**
    * @return string
    */
    public function body();

    /**
    * @return \App\Event
    */
    public function mail($id);
}