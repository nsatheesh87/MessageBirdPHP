<?php
namespace Controllers;
use Http\JsonRequest as Request;
use Http\JsonResponse as Response;
use Exceptions\AppException as Exception;

class MessageController extends Controller
{
    private $ServiceContainer;
    private $request;
    private $response;

    const FIELDS_ARE_MISSING_MESSAGE      = 'Some fields are missing';
    const INVALID_MESSAGE_TYPE            = 'Invalid message type, must be string';
    const INVALID_ORIGINATOR_TYPE         = 'Invalid originator type, must be (string) MessageBird';
    const INVALID_RECIPIENT_TYPE          = 'Invalid recipient type, must be integer';

    const INVALID_STRING_TYPE             = 'invalid_string_type';
    const INVALID_INTEGER_TYPE            = 'invalid_integer_type';
    const FIELDS_MISSING_CODE             = 'missing_fields';

    const RECIPIENT                       = 'recipient';
    const MESSAGE                         = 'message';
    const ORIGINATOR                      = 'originator';

    const ORIGINATOR_VALUE                = 'MessageBird';

    const INVALID_REQUEST_CODE            = 502;

    public function __construct()
    {
        parent::__construct();
        $this->ServiceContainer =$this->container;
        $this->request          = new Request();
        $this->response         = new Response();
    }

    private function isValidFormat($requestData)
    {
        if(!@$requestData[self::RECIPIENT]
            || !@$requestData[self::ORIGINATOR]
            || !@$requestData[self::MESSAGE]
        ){
            throw new Exception(self::FIELDS_ARE_MISSING_MESSAGE, self::INVALID_REQUEST_CODE);
        }

        return true;
    }

    private function validateInput($requestData)
    {
        if(!filter_var($requestData[self::RECIPIENT], FILTER_VALIDATE_FLOAT)){
            throw new Exception(self::INVALID_RECIPIENT_TYPE, self::INVALID_REQUEST_CODE);
        }

        if($requestData[self::ORIGINATOR] !== self::ORIGINATOR_VALUE){
            throw new Exception(self::INVALID_ORIGINATOR_TYPE, self::INVALID_REQUEST_CODE);
        }
    }
    public function sendSmsMessage()
    {
        $requestData = $this->request->data();

        try {
            $this->isValidFormat($requestData);
        } catch (Exception $e) {
            $this->response->sendError($e);
        }

        try {
            $this->validateInput($requestData);
        } catch (Exception $e) {
            $this->response->sendError($e);
        }

        $messaging = $this->ServiceContainer->get('MessageBird');

        $messaging->composeMessage(
            filter_var($requestData[self::MESSAGE], FILTER_SANITIZE_STRING),
            $requestData[self::RECIPIENT],
            $requestData[self::ORIGINATOR]
        );

        $result = $messaging->send();

        $this->response->send($result);
    }

}
