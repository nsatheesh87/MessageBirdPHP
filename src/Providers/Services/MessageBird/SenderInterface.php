<?php

namespace Providers\Services\MessageBird;

/**
 * Interface SenderInterface
 * @package Providers\Services\MessageBird
 */
interface SenderInterface
{
    /**
     * @param string $message
     * @param string $recipients
     * @param string $originator
     * @return mixed
     */
    public function composeMessage(
        string  $message, 
        string $recipients,
        string  $originator 
   );

    /**
     * @return mixed
     */
    public function send();
}
