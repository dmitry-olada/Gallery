<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 07.01.16
 * Time: 21:28
 */

namespace Core\Http;


class Response
{
    protected $headers = array();

    protected $content;

    protected $status = 200;

    protected $version = 'HTTP/1.1';

    public static $statusTexts = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        226 => 'IM Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Reserved',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Reserved for WebDAV advanced collections expired proposal',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates (Experimental)',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
    );

    public function __construct($content = '', $status = 200)
    {
        $this->setContent($content);
        $this->setStatus($status);
    }


    public function setStatus($status)
    {
        $status = (int)$status;
        if (array_key_exists($status, self::$statusTexts)) {
            $this->status = $status;
            return $this;
        } else {
            throw new \Exception('Illegal status code!');
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    public function getHeaders($key = null)
    {
        return $key?$this->headers[$key]:$this->headers;
    }

    /*public function redirect($url, $status = 302)
    {
        $this->setHeaders(['Location' => trim($url)]);
        $this->setStatus($status);
        return $this;
    }*/

    public function redirect($url = null){
        $url = $url?$url:'/';
        header('Location: '.$url, true, 302);
        die;
    }

    public function setHttpVersion($version)
    {
        if ((stristr($version, '1.0') === true) || stristr($version, '1.1') === true) {
            if (stristr($version, 'HTTP/') === true) {
                $this->version = $version;
            } else {
                $this->version = 'HTTP/'.$version;
            }
        } else {
            throw new \Exception('Illegal HTTP version!');
        }
    }

    public function sendHeaders()
    {
        $line = $this->version.' '.$this->status.' '.self::$statusTexts[$this->status];
        header($line, true, $this->status);
        foreach ($this->headers as $key => $value) {
            header($key.':'.$value);
        }
        return $this;
    }

    public function send()
    {
        $this->sendHeaders();
        echo $this->content;
        return $this;
    }


}