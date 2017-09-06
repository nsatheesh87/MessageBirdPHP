<?php

namespace Http;
use StdClass;

class JsonResponse
{
    protected $response;

    public function __construct(){}

    private function setJsonHeader(int $code = 200)
    {
        header("HTTP/1.1 {$code}");
        header('Content-Type: application/json');
    }


    public function send($response, $code = 200)
    {
        $this->setJsonHeader($code);
        echo $this->composeObject($response);
        die();
    }

    public function sendError($error) {
        $this->setJsonHeader($error->getCode());
        $response = ['error' => [
            'code'  => $error->getCode(),
            'message' => $error->getMessage()
        ]];
        echo json_encode($response);
        die();
    }
    public function composeObject($response)
    {
        $res = new StdClass();
        $res->data = $response;
        return json_encode($res);
    }
}