<?php

namespace Ido\Tools\Util;


class FileReader
{

    public static function readAllLine($file, $callback = null)
    {

        $s = file_get_contents($file);
        $arr = explode(PHP_EOL, $s);
        if (is_callable($callback)) {
            $arr = collect($arr)->map($callback)->values()->all();
        }

        return $arr;
    }

    public static function writeLines($file,$lines)
    {
        file_put_contents($file,implode(PHP_EOL,$lines));
    }
}