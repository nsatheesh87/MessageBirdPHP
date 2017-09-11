<?php
namespace Providers\Services;

/**
 * Class ServiceContainer
 * @package Providers\Services
 */
class ServiceContainer
{
    /**
     * @var array
     */
    private $arguments;

    /**
     * ServiceContainer constructor.
     */
    public function __construct()
    {
        $this->services  = ServiceRegister::getServices();
        $this->arguments = [];
    }

    /**
     * @param $service
     * @return object
     */
    public function get($service)
    {
        $service     = @$this->services[$service];
        $reflection  = new \ReflectionClass($service["class"]);

        foreach ($service["arguments"] as $arg) {
            $this->arguments[] = new $arg();
        }
        return $reflection->newInstanceArgs($this->arguments);
    }
}