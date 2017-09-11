<?php

namespace Config;

/**
 * Class Config
 *
 * @package MessageBird
 */
class Config
{
    const key = 'YOUR ACCESS KEY';

    /**
     * @return string
     */
    public static function getActivationKey()
    {
       return self::key;
    }
}