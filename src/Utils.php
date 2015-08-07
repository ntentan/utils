<?php

namespace ntentan\utils;

class Utils
{
    public static function factory(&$value, $initiator)
    {
        if ($value === null) {
            $value = $initiator();
        }
        return $value;
    }

}
