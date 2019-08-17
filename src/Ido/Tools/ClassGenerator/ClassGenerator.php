<?php
/**
 * Created by PhpStorm.
 * User: liurongdong
 * Date: 2017/5/22
 * Time: 下午9:49
 */

namespace Ido\Tools\ClassGenerator;


use Util\Tpl;

class ClassGenerator
{

    static $namesoace = '';
    /**
     * @param $className
     * @param array  $array 示例数据数组
     * @param array $option ['isMapper'=>false,'isGenerateByArray'=>false,]
     */
    public static function generateByArray($className, $array, $option = [])
    {
        $namespace = str_replace('/', '\\', trim(dirname(str_replace('\\', '/', $className)), './\\'));
        //初始化清除类
        if(static::$namesoace!= $namespace){
            \Storage::disk('local')->deleteDirectory('class');
        }
        static::$namesoace = $namespace;
        $className = basename(str_replace('\\', '/', $className));
        $className = \Illuminate\Support\Str::studly($className);
        $properties = [];
        foreach ($array as $k => $v) {
            if (!is_array($v)) {
                $properties[] = [$k, 'className' => gettype($v), 'isArray' => false, 'isObj' => false];
            } else {
                $childName = empty($namespace) ? \Illuminate\Support\Str::studly($k) : $namespace . '\\' . \Illuminate\Support\Str::studly($k);
                if (isset($v[0])) {
                    //对象数组
                    if (is_array($v[0])) {
                        $properties[] = [$k, 'className' => '\\' . $childName, 'isArray' => true, 'isObj' => true];
                        static::generateByArray('\\' . $childName, $v[0], $option);
                    } else {
                        //标量数组
                        $properties[] = [$k, 'className' => gettype($v[0]), 'isArray' => true, 'isObj' => false];

                    }
                    //单对象
                } elseif (count($v) > 0) {
//                    echo $childName.PHP_EOL;
                    $properties[] = [$k, 'className' => '\\' . $childName, '\\' . $childName, 'isArray' => false, 'isObj' => true];
                    static::generateByArray('\\' . $childName, $v, $option);
//                    var_dump($childName,$v);

                }
            }
        }
        $isMapper = $option['isMapper']??false;
        $isGenerateByArray = $option['isGenerateByArray']??false;
        $code = Tpl::parse(file_get_contents(__DIR__ . '/Tpl/class.tpl'), [
            'properties' => $properties,
            'namespace' => $namespace,
            'className' => $className,
            'isMapper' => $isMapper,
            'isGenerateByArray' => $isGenerateByArray,
        ])  ;

        \Storage::disk('local')->put('class/' . $className . ' .php', $code);
    }

    protected static $mapers = [];

    /**
     * @param $className
     * @param array  $array 示例数据数组
     * @param array $option ['isMapper'=>false,'isGenerateByArray'=>false,]
     * @param bool $init
     */
    public static function generateMapper($className, $array, $option = [], $init=true)
    {
        $namespace = str_replace('/', '\\', trim(dirname(str_replace('\\', '/', $className)), './\\'));
        //初始化清除类
        if($init){
            \Storage::disk('local')->deleteDirectory('class');
        }
        $className = basename(str_replace('\\', '/', $className));
        $className = \Illuminate\Support\Str::studly($className);
        $properties = [];
        foreach ($array as $k => $v) {
            if (!is_array($v)) {
                $properties[] = [$k, 'className' => gettype($v), 'isArray' => false, 'isObj' => false];
            } else {
                $childName =  \Illuminate\Support\Str::studly($k);
                if (isset($v[0])) {
                    //对象数组
                    if (is_array($v[0])) {
                        $properties[] = [$k, 'className' =>   $childName, 'isArray' => true, 'isObj' => true];
                        static::generateMapper('\\' . $childName, $v[0], $option,false);
                    } else {
                        //标量数组
                        $properties[] = [$k, 'className' => gettype($v[0]), 'isArray' => true, 'isObj' => false];

                    }
                    //单对象
                } elseif (count($v) > 0) {
//                    echo $childName.PHP_EOL;
                    $properties[] = [$k, 'className' =>  $childName, 'isArray' => false, 'isObj' => true];
                    static::generateMapper('\\' . $childName, $v, $option,false);
//                    var_dump($childName,$v);

                }
            }
        }

        static::$mapers[] = [
            'properties' => $properties,
            'namespace' => $namespace,
            'className' => $className,
        ];
        if($init){
            $code = Tpl::parse(file_get_contents(__DIR__ . '/Generate/mapper.tpl'),[
                'mappers'=> static::$mapers,
                'namespace' => $namespace,
                'className' => 'Mapper'.$className,
            ])  ;
            \Storage::disk('local')->put('class/' . 'Mapper'.$className . ' .php', $code);
        }
    }

}