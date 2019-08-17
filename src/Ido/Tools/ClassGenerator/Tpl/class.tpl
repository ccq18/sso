<?php
{% if(!empty($namespace)){ %}
namespace {{$namespace}};
{% } %}


class {{$className}}
{
{% foreach ($properties as $propertie){ %}

    /**
     * @var {{$propertie['className']}} {{$propertie['isArray']?"[]":""}}

     */
    protected ${{$propertie[0]}};
{% } %}
{% foreach ($properties as $propertie){ %}

    /**
     * @return {{$propertie['className']}} {{$propertie['isArray']?"[]":""}}

     */
    public function get{{\Illuminate\Support\Str::studly($propertie[0])}}()
    {
        return $this->{{$propertie[0]}};
    }

    /**
     * @param {{$propertie['className']}} {{$propertie['isArray']?"[]":""}} ${{$propertie[0]}}

     */
    public function set{{\Illuminate\Support\Str::studly($propertie[0])}}(${{$propertie[0]}})
    {
        $this->{{$propertie[0]}} = ${{$propertie[0]}};
    }
{% } %}

{% if ($isGenerateByArray){ %}
    /**
    * @param array $arr
    * @return {{$className}}

    */
    public static function generateByArray($arr)
    {
        $obj = new {{$className}}();

{% foreach ($properties as $propertie){ %}
        if(isset($arr['{{$propertie[0]}}'])){
{% if($propertie['isObj']){ %}
            if(is_array($arr['{{$propertie[0]}}'])){
{% if($propertie['isArray']){ %}
${{$propertie[0]}} = [];
                foreach($arr['{{$propertie[0]}}'] as $v){
                    if(is_array($v)){
                        ${{$propertie[0]}}[] = {{$propertie['className']}}::generateByArray($v);
                    }
                }
                $obj->set{{\Illuminate\Support\Str::studly($propertie[0])}}(${{$propertie[0]}});
{% }else{ %}
                $obj->set{{\Illuminate\Support\Str::studly($propertie[0])}}({{$propertie['className']}}::generateByArray($arr['{{$propertie[0]}}']));

{% } %}
            }
{% }else{ %}
            $obj->set{{\Illuminate\Support\Str::studly($propertie[0])}}($arr['{{$propertie[0]}}']);
{% } %}
        }
{% } %}

        return $obj;
    }
{% } %}

{% if ($isMapper){ %}

    /**
    * @param  $arr
    * @return {{$className}}

    */
    public static function mapper($arr)
    {
        $obj = new {{$className}}();
{% foreach ($properties as $propertie){ %}
{% if($propertie['isObj']){ %}
        if(is_array($arr['{{$propertie[0]}}'])){
{% if($propertie['isArray']){ %}
            ${{$propertie[0]}} = [];
            foreach($arr['{{$propertie[0]}}'] as $v){
                if(is_array($v)){
                    ${{$propertie[0]}}[] = {{$propertie['className']}}::mapper($v);
                }
            }
            $obj->set{{\Illuminate\Support\Str::studly($propertie[0])}}(${{$propertie[0]}});

{% }else{ %}
            $obj->set{{\Illuminate\Support\Str::studly($propertie[0])}}({{$propertie['className']}}::mapper($arr['{{$propertie[0]}}']));
{% } %}
        }
{% }else{ %}
        $obj->set{{\Illuminate\Support\Str::studly($propertie[0])}}($arr['{{$propertie[0]}}']);
{% } %}
{% } %}

        return $obj;
    }
{% } %}
}