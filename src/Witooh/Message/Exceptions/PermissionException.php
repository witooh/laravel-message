<?php

namespace Witooh\Message\Exceptions;


class PermissionException extends \Exception {

    public function __construct($message = null, $code = 403)
    {
        parent::__construct($message ?: 'Permission denie', $code);
    }
}