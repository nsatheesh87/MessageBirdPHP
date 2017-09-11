<?php

namespace Http;

/**
 * Class JsonRequest
 * @package Http
 */
class JsonRequest
{
    /**
     * JsonRequest constructor.
     */
    public function __construct(){}

    /**
     * @return mixed
     */
    public function data()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
}