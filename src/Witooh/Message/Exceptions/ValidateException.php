<?php

namespace Witooh\Message\Exceptions;

class ValidateException extends \Exception {

    protected $messages;

    /**
     * We are adjusting this constructor to receive an instance
     * of the validator as opposed to a string to save us some typing
     * @param \Witooh\Validators\IValidator $validator failed validator object
     */
    public function __construct($validator)
    {
        $this->messages = $validator->getErrors();
        parent::__construct($this->messages, 400);
    }

    public function getMessages()
    {
        return $this->messages;
    }

}