<?php

namespace App\Helpers;

class ErrorResponse {

    public $message = "";
    public $status = false;

    function __construct($message) {
        $this->message = $message;
    }

}

