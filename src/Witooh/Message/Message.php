<?php

namespace Witooh\Message;

use App;
use Illuminate\Support\Collection;
use Illuminate\Support\Contracts\ArrayableInterface;
use Response;
use Witooh\Message\Exceptions\AuthenticateException;
use Witooh\Message\Exceptions\NotFoundException;
use Witooh\Message\Exceptions\PermissionException;
use Witooh\Message\Exceptions\ValidateException;

class Message
{
    const SUCCESS = 200;
    const ERROR = 500;
    const VALIDATION = 501;
    const PERMISSION = 400;
    const AUTH = 401;
    const NOTFOUND = 404;

    protected $header;

    protected $body;

    protected $isRaw;


    function __construct()
    {
        $this->header = null;
        $this->body = null;
        $this->isRaw = false;

        App::error(
            function (ValidateException $exception, $code) {
                $res = Response::make($exception->getMessage(), $code);
                $res->headers->add(
                    array(
                        'Content-Type' => 'application/json'
                    )
                );

                return $res;
            }
        );

        App::error(
            function (AuthenticateException $exception, $code) {
                $res = Response::make($exception->getMessage(), $code);
                $res->headers->add(
                    array(
                        'Content-Type' => 'application/json'
                    )
                );

                return $res;
            }
        );

        App::error(
            function (NotFoundException $exception, $code) {
                $res = Response::make($exception->getMessage(), $code);
                $res->headers->add(
                    array(
                        'Content-Type' => 'application/json'
                    )
                );

                return $res;
            }
        );

        App::error(
            function (PermissionException $exception, $code) {
                $res = Response::make($exception->getMessage(), $code);
                $res->headers->add(
                    array(
                        'Content-Type' => 'application/json'
                    )
                );

                return $res;
            }
        );
    }

    public function raw($data)
    {
        $this->clear();
        $this->isRaw = true;
        if ($data instanceof Collection) {
            $this->body = $data->toArray();
        } else {
            $this->body = $data;
        }

        return $this->toArray();
    }

    public function success($data, array $header = array())
    {
        $this->clear();
        if ($data instanceof ArrayableInterface) {
            $this->body = $data->toArray();
        } else {
            $this->body = $data;
        }
        $this->header = array(
            'status' => self::SUCCESS,
            'message' => 'Success',
        );
        $this->header = array_merge($this->header, $header);

        return $this->toArray();
    }

    public function permission($message = 'Permission Denied', $header = array())
    {
        $this->clear();
        $this->header = array(
            'status' => self::PERMISSION,
        );
        $this->header = array_merge($this->header, $header);
        $this->header['message'] = $message;

        return $this->toArray();
    }

    public function error($message, array $data = array(), array $header = array())
    {
        $this->clear();
        $this->body = $data;
        $this->header = array(
            'status' => self::ERROR,
            'message' => 'Error',
        );
        $this->header = array_merge($this->header, $header);
        $this->header['message'] = $message;

        return $this->toArray();
    }

    public function validation($data = array(), array $header = array())
    {
        $this->clear();
        if ($data instanceof ArrayableInterface) {
            $this->body = $data->toArray();
        } else {
            $this->body = $data;
        }
        $this->header = array(
            'status' => self::VALIDATION,
            'message' => 'Validation Failed',
        );
        $this->header = array_merge($this->header, $header);

        return $this->toArray();
    }

    public function auth($message = 'Authenticate is required', array $header = array())
    {
        $this->clear();
        $this->header = array(
            'status' => self::AUTH,
        );
        $this->header = array_merge($this->header, $header);
        $this->header['message'] = $message;

        return $this->toArray();
    }

    public function notfound($message = 'The request not found', $header = array())
    {
        $this->clear();
        $this->body = null;
        $this->header = array(
            'status' => self::NOTFOUND,
        );
        $this->header = array_merge($this->header, $header);
        $this->header['message'] = $message;

        return $this->toJson();
    }

    public function clear()
    {
        $this->body = null;
        $this->header = null;
        $this->isRaw = false;
    }

    public function toArray()
    {
        if ($this->isRaw) {
            $arr = $this->body;
        } else {
            $arr = array();
            if (!empty($this->header)) {
                $arr['header'] = $this->header;
            }
            if (!empty($this->body)) {
                $arr['body'] = $this->body;
            }
        }

        return $arr;
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}