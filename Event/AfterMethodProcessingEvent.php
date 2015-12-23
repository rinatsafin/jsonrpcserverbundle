<?php

namespace TSCore\JsonRpcServerBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use TSCore\JsonRpcServerBundle\Response\RpcResponse;

class AfterMethodProcessingEvent extends Event
{
    /**
     * @var mixed
    */
    private $response;

    private $rpcIdRequest;

    public function __construct($response, $rpcIdRequest = null)
    {
        $this->response = $response;
        $this->rpcIdRequest = $rpcIdRequest;
    }

    public function getRpcIdRequest()
    {
        return $this->rpcIdRequest;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }
}