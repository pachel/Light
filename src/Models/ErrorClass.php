<?php

namespace Pachel\Light\src\Models;

class ErrorClass
{
    public $ERROR_CODE;
    public $ERROR_MESSAGE;

    public function __construct($ERROR_CODE,$ERROR_MESSAGE)
    {
        $this->ERROR_CODE = $ERROR_CODE;
        $this->ERROR_MESSAGE = $ERROR_MESSAGE;
    }
}