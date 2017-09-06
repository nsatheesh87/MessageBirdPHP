<?php

namespace Http;

class JsonRequest
{
    public function __construct(){}
    public function data()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
}