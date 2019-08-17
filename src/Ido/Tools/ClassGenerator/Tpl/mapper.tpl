<?php
{% if(!empty($namespace)){ %}
namespace {{$namespace}};
{% } %}


class {{$className}}
{

{% foreach($mappers as $mapper){
$properties = $mapper['properties'];
$namespace = $mapper['namespace'];
$className = $mapper['className'];
%}
    /**
    * @param  $arr
    * @return array

    */
    public static function mapper{{$className}}($arr)
    {
        $rs = [];
{% foreach ($properties as $propertie){ %}
{% if($propertie['isObj']){ %}
        if(is_array($arr['{{$propertie[0]}}'])){
{% if($propertie['isArray']){ %}
            ${{$propertie[0]}} = [];
            foreach($arr['{{$propertie[0]}}'] as $v){
                if(is_array($v)){
                    ${{$propertie[0]}}[] = static::mapper{{$propertie['className']}}($v);
                }
            }
            $rs['{{$propertie[0]}}'] = ${{$propertie[0]}};

{% }else{ %}
            $rs['{{$propertie[0]}}'] = static::mapper{{$propertie['className']}}($arr['{{$propertie[0]}}']);
{% } %}
        }
{% }else{ %}
        $rs['{{$propertie[0]}}'] = $arr['{{$propertie[0]}}'];
{% } %}
{% } %}

        return $rs;
    }

{% } %}

}