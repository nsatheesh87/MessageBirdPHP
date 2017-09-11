<?php

namespace MessageBird;
use Controllers\MessageController;

/**
 * Class Application
 * @package MessageBird
 */
class Application
{
    /**
     * Application constructor.
     */
    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function run()
    {
        //PS Fix it: I have only one purpose for this whole application with a Single Controller, Passing controller name directly
        //Can be improved when i have multiple url with Route access method.
        return call_user_func_array(
            [
                new MessageController(),
                'sendSmsMessage'
            ],
            []
        );
    }
}