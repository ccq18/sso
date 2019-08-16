<?php

namespace Dto;



class DtoBuilder
{
    protected $data;
    protected $result;
    static $formaters = [];

    public function __construct($data)
    {
        $this->data = $data;
    }

    public static function addFormater($format, $formater)
    {
        if (is_callable($formater)) {
            static::$formaters[$format] = $formater;
        } else if (function_exists($formater)) {
            static::$formaters[$format] = function ($value) use ($formater) {
                return call_user_func_array($formater, [$value]);
            };
        }
    }


    public function getResult()
    {
        return $this->result;

    }


    public function add($key, $params = [])
    {
        $value = $this->getValue($key);
        if (isset($params['using'])) {
            $value = resolve(DtoService::class)->transfer($value, $params['using']);
        }
        if (isset($params['format'])) {
            $value = $this->format($params['format'], $value);
        }

        if (isset($params['as'])) {
            $this->result[$params['as']] = $value;
        } else {
            $this->result[$key] = $value;
        }

        return $this;
    }

    protected function format($formater, $value)
    {
        if (is_callable($formater)) {
            return call_user_func_array($formater, [$value]);
        } elseif (is_string($formater)) {
            return $this->byFormater($formater,$value);
        }
    }


    /**
     * @param $format
     * @param $value
     * @return mixed
     * @throws \Exception
     */
    protected function byFormater($format, $value)
    {
        if (isset(static::$formaters[$format])) {

            return call_user_func_array(static::$formaters[$format], [$value, $format]);
        } else {
            throw new \Exception("format:{$format}不存在");
        }
    }

    protected function getValue($key)
    {
        $keys = explode('.', $key);
        $d = $this->data;
        foreach ($keys as $k){
            $d = $d[$k];
        }
        return $d;
    }



}