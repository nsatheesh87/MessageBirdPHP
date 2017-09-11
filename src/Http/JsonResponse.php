<?php

namespace Http;
use StdClass;

/**
 * Class JsonResponse
 * @package Http
 */
class JsonResponse
{
    /**
     * @var $response
     */
    protected $response;

    /**
     * JsonResponse constructor.
     */
    public function __construct(){}

    /**
     * @param int $code
     */
    private function setJsonHeader(int $code = 200)
    {
        header("HTTP/1.1 {$code}");
        header('Content-Type: application/json');
    }

    /**
     * @param $response
     * @param int $code
     */
    public function send($response, $code = 200)
    {
        $this->setJsonHeader($code);
        echo $this->composeObject($response);
        die();
    }

    /**
     * @param $error
     */
    public function sendError($error) {
        $this->setJsonHeader($error->getCode());
        $response = ['error' => [
            'code'  => $error->getCode(),
            'message' => $error->getMessage()
        ]];
        echo json_encode($response);
        die();
    }

    /**
     * @param $response
     * @return string
     */
    public function composeObject($response)
    {
        $res = new StdClass();
        $res->data = $response;
        return json_encode($res);
    }
}