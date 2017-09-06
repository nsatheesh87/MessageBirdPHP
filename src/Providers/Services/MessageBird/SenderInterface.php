<?php

namespace Providers\Services\MessageBird;

interface SenderInterface
{
    public function composeMessage(
        string  $message, 
        string $recipients,
        string  $originator 
   );

    public function send();
}
