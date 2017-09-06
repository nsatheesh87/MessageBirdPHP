<?php

namespace Config;

class Config
{
    const key = 'YOUR ACCESS KEY';

    public static function getActivationKey()
    {
       return self::key;
    }
}