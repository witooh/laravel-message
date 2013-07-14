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
    }

    /**
     * @param $data
     * @return array|null
     */
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

    /**
     * @param \Illuminate\Support\Collection|array $data
     * @param array $header
     * @return array|null
     */
    public function success($data = array(), array $header = array())
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

    /**
     * @param string $message
     * @param array $header
     * @return array|null
     */
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

    /**
     * @param $message
     * @param \Illuminate\Support\Collection|array $data
     * @param array $header
     * @return array|null
     */
    public function error($message, array $data = array(), array $header = array())
    {
        $this->clear();
        if ($data instanceof ArrayableInterface) {
            $this->body = $data->toArray();
        } else {
            $this->body = $data;
        }
        $this->header = array(
            'status' => self::ERROR,
            'message' => 'Error',
        );
        $this->header = array_merge($this->header, $header);
        $this->header['message'] = $message;

        return $this->toArray();
    }

    /**
     * @param \Illuminate\Support\Collection|array $data
     * @param array $header
     * @return array|null
     */
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

    /**
     * @param string $message
     * @param array $header
     * @return array|null
     */
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

    /**
     * @param string $message
     * @param array $header
     * @return string
     */
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

    /**
     *
     */
    public function clear()
    {
        $this->body = null;
        $this->header = null;
        $this->isRaw = false;
    }

    /**
     * @return array|null
     */
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

    /**
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}