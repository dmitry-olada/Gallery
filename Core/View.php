<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 08.01.16
 * Time: 21:31
 */

namespace Core;


class View
{
    protected $path;

    public $data = array();

    public function setData($key, $value)
    {
        if(is_array($key) && is_array($value)){
            $this->data = array_merge(array_combine($key, $value), $this->data);
            return $this;
        }elseif(is_string($key)){
            $this->data[$key] = is_array($value) ? implode(',', $value) : $value;
            return $this;
        }else{
            // TODO: INCORRECT TYPE OF DATA EXCEPTION
        }
    }

    public function render($path)
    {
        $this->path = $path;
        if($this->data){
            extract($this->data);
        }
        ob_start();
        include $this->path;
        return ob_get_clean();
    }
}