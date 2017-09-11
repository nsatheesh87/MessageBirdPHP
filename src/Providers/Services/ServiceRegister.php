<?php
namespace Providers\Services;
#use Helpers\Config;

/**
 * Class ServiceRegister
 * @package Providers\Services
 */
class ServiceRegister
{
    /**
     * @return array
     */
    public static function getServices()
    {
        return ['MessageBird' =>
            ['class' => 'Providers\\Services\\MessageBird\\Sender', 'arguments' => []]
        ];
    }
}
