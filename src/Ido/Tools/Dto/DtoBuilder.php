<?php

namespace Ido\Tools\Dto;



use BadMethodCallException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

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

        if (isset($params['transfer'])) {
            $value = $this->transfer($params['transfer'], $value);
        }


        if (isset($params['as'])) {
            $this->result[$params['as']] = $value;
        } else {
            $this->result[$key] = $value;
        }

        return $this;
    }

    protected function transfer($transfer, $value){
        if(!is_array($transfer)){
            throw new \Exception(" transfer 类型不正确");
        }
        return $transfer[$value]??null;
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
            $d = $this->getValueByKey($d,$k);
        }
        return $d;
    }

    protected function getValueByKey($carry,$item){
        if(is_array($carry)){
            return $carry[$item];
        }
        if ($carry instanceof Model && $carry->relationLoaded($item)) {
            return $carry->getRelation($item);
        }

        //relation or method
        if (method_exists($carry, $item)) {
            $callResult = call_user_func([$carry, $item]);
            //cache the relation
            if ($callResult instanceof Relation) {
                $relationResult = $callResult->getResults();
                $carry->setRelation($item, $relationResult);
                return $relationResult;
            } else {
                return $callResult;
            }
        }

        //enum
        if (is_callable([$carry, $item])) {
            try {
                return call_user_func([$carry, $item]);
            } catch (BadMethodCallException $e) {
            }
        }
        dd(object_get($carry, $item));

        //attribute
        return object_get($carry, $item);
    }


}