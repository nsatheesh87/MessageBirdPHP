<?php

namespace Controllers;
use Providers\Services\ServiceContainer;

abstract class Controller{

    public $container;


    public function __construct(){
        $this->container = new ServiceContainer();
    }

}
