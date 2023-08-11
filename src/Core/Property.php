<?php

namespace Core;

class Property
{

    static public function GetValue($structure, string $key, $type = "string", $default = null)
    {
        if(!is_array($structure))
        {
            // normalize to one interface
            $structure = (array)$structure;
        }

        if(!isset($structure[$key]) || !key_exists($key, $structure))
            return $default;

        $ret = $structure[$key];
        switch(strtolower($type))
        {
            default:
            case "string":
                $ret = trim(html_entity_decode((string)$ret, ENT_QUOTES, 'utf-8'));
                break;
            case "int":
            case "number":
                $ret = (int)$ret;
                break;
            case "float":
                $ret = (float)$ret;
                break;
            case "bool":
                $ret = $ret ? true : false;
                break;
            case "date":
            case "datetime":
                $ret = new DateTime($ret);
                break;
        }

        return $ret;
    }
}
