<?php

namespace Witooh\Message\Facade;

use Illuminate\Support\Facades\Facade;

class Message extends Facade {
    protected static function getFacadeAccessor() { return 'message-response'; }
}
