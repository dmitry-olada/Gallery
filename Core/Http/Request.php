<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 07.01.16
 * Time: 21:28
 */

namespace Core\Http;


class Request
{
    protected $global = array();

    public function __construct()
    {
        if ($this->isPost()) {
            $this->global = array_map('stripslashes', $_POST);
        } else {
            $this->global = array_map('stripslashes', $_GET);
        }
    }

    public function get($key = null)
    {
        if (null !== $key && !array_key_exists($key, $this->global)) {
            return null;
        }
        return $key?$this->global[$key]:$this->global;
    }

    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getUri()
    {
        $uri = trim(trim($_SERVER['REQUEST_URI']), '/');
        return $uri;
    }

    public function getHeader($header)
    {
        $header = strtoupper(strtr($header, "-", "_"));
        if (array_key_exists($header, $_SERVER)) {
            return $_SERVER[$header];
        } elseif (array_key_exists('HTTP'.$header, $_SERVER)) {
            return $_SERVER['HTTP'.$header];
        } else {
            return null;
        }
    }

    public function isPost()
    {
        return ($_SERVER['REQUEST_METHOD'] == 'POST');
    }

    public function isGet()
    {
        return ($_SERVER['REQUEST_METHOD'] == 'GET');
    }
}