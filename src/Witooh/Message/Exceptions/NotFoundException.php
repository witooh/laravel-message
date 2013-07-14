<?php

namespace Witooh\Message\Exceptions;


class NotFoundException extends \Exception {

    public function __construct($message = null, $code = 404)
    {
        parent::__construct($message ?: 'Resource not found', $code);
    }
}