<?php
namespace Providers\Services;
#use Helpers\Config;

class ServiceRegister
{
    public static function getServices()
    {
        return ['MessageBird' =>
            ['class' => 'Providers\\Services\\MessageBird\\Sender', 'arguments' => []]
        ];
    }
}
