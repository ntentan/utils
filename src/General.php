<?php

namespace ntentan\utils;

class General
{
    public static function factory(&$value, $initiator) {
        if($value === null) {
            $value = $initiator();
        }
        return $value;
    }
}
