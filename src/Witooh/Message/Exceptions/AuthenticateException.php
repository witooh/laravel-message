<?php

namespace Witooh\Message\Exceptions;


class AuthenticateException extends \Exception {

    public function __construct($message = null, $code = 401)
    {
        parent::__construct($message ?: 'Authenticate is required', $code);
    }
}