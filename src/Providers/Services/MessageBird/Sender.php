<?php

namespace Providers\Services\MessageBird;
use Http\JsonResponse as Response;
use Config\Config as Config;
use StdClass;

/**
 * Class Sender
 * @package Providers\Services\MessageBird
 */
class Sender implements SenderInterface
{

    const SINGLE_MESSAGE_LENGTH = 160;
    //One way of sending concatenated SMS (CSMS) is to split the message into 153 7-bit character parts (134 octets)
    CONST SPLIT_MESSAGE_LENGTH  = 153;
    const THROTTLE              = 1;
    const DATA_TYPE             = 'binary';
    const DATA_CODING           = 'auto';
    const UDH_HEADER            = '050003';
    const INTERNAL_SERVER_ERROR = '500';

    /**
     * @var
     */
    protected $message;
    /**
     * @var
     */
    protected $recipients;
    /**
     * @var
     */
    protected $originator;
    /**
     * @var
     */
    protected $isSingleMessage;
    /**
     * @var string
     */
    protected $key;
    /**
     * @var Response
     */
    protected $response;

    /**
     * Sender constructor.
     */
    public function __construct()
    {
        $this->key                    = config::getActivationKey();
        $this->MessageBird            = new \MessageBird\Client($this->key);
        $this->messageObject          = new \MessageBird\Objects\Message();
        $this->response               = new Response();
    }

    /**
     *
     */
    private function createMessage()
    {
       // echo $this->recipients; exit;
        $this->messageObject->originator              = $this->originator;
        $this->messageObject->recipients              = ['0' => $this->recipients];
        $this->messageObject->body                    = $this->message;
        $this->messageObject->datacoding              = self::DATA_CODING;

    }

    /**
     * @param $message
     * @param $udh
     */
    private function createBinaryMessage($message, $udh)
    {
        $this->messageObject->originator              = $this->originator;
        $this->messageObject->recipients              = $this->recipients;
        $this->messageObject->body                    = $message;
        $this->messageObject->datacoding              = self::DATA_CODING;
        $this->messageObject->type                    = self::DATA_TYPE;
        $this->messageObject->typeDetails             = new StdClass();
        $this->messageObject->typeDetails->udh        = $udh;
    }

    /**
     * @param $message
     * @return array
     */
    private function createUDHeader($message)
    {
       $totalMsgParts     = ceil(strlen($message)/self::SPLIT_MESSAGE_LENGTH);
       $totalMsgPartsHex  = dechex($totalMsgParts);
       
       if(strlen($totalMsgPartsHex) == 1) $totalMsgPartsHex = "0".$totalMsgPartsHex;
       
       $messageCharIndexStart       = 0;                   
       $UDH                         = [];
       $userHeader                  = self::UDH_HEADER.$totalMsgPartsHex;

       for ($i = 1; $i <= $totalMsgParts; $i++) {
            $messagePart = substr($message, $messageCharIndexStart, self::SPLIT_MESSAGE_LENGTH);
            $messageCharIndexStart += self::SPLIT_MESSAGE_LENGTH;
            $currentMessagePartsNoHex = dechex($i);

            if (strlen($currentMessagePartsNoHex) == 1) $currentMessagePartsNoHex = "0".$currentMessagePartsNoHex;   

            array_push($UDH, ['userHeader' => $userHeader.$currentMessagePartsNoHex, 'message' => $messagePart]);
       }

       return $UDH;           
    }

    /**
     * @param $message
     * @return bool
     */
    private function isSingleMessage($message)
    {
        return strlen($message) < self::SINGLE_MESSAGE_LENGTH;
    }

    /**
     * @param string $message
     * @param string $phoneNumbers
     * @param string $originator
     */
    public function composeMessage(string $message, string $phoneNumbers, string $originator)
    {
      $this->message        = $message;
      $this->recipients     = $phoneNumbers;
      $this->originator     = $originator;

      if($this->isSingleMessage($message)){
          $this->isSingleMessage = true;
          return;
      }
    }

    /**
     * @return \MessageBird\Objects\Balance|\MessageBird\Objects\Hlr|\MessageBird\Objects\Lookup|\MessageBird\Objects\Message|\MessageBird\Objects\Verify|\MessageBird\Objects\VoiceMessage
     */
    public function send()
    {
        try {
            if($this->isSingleMessage){
                $this->sleep(self::THROTTLE);
                $this->createMessage();
                $result = $this->MessageBird->messages->create($this->messageObject);
            }else{
                $UDH = $this->createUDHeader($this->message);

                foreach ($UDH as $body) {
                    var_dump($body);
                    $this->sleep(self::THROTTLE);
                    $this->createBinaryMessage($body['message'], $body['userHeader']);
                    $result = $this->MessageBird->messages->create($this->messageObject);
                }
            }

            return $result;
        
        } catch (\MessageBird\Exceptions\BalanceException $e) {
            $this->response->sendError($e);
        } catch (\MessageBird\Exceptions\AuthenticateException $e) {
            $this->response->sendError($e);
        } catch (\Exception $e) {
            $this->response->sendError($e);
        }
    }

    private function sleep($seconds)
    {
      sleep($seconds);
    }

}
