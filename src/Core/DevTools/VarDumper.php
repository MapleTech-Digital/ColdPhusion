<?php

namespace Core\DevTools;

class VarDumper
{
    public static function Dump($variable, $die = false, $format = true)
    {
        if($format) echo "<pre>";
        var_dump($variable);
        if($format) echo "</pre>";
        if($die) die();

    }
}
