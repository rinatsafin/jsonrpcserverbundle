<?php

namespace TSCore\JsonRpcServerBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use TSCore\JsonRpcServerBundle\Request\RpcRequest;
use TSCore\JsonRpcServerBundle\Server\PreDispatchResult;

class BeforeMethodProcessingEvent extends Event
{
    /**
     * @var RpcRequest
    */
    private $rpcRequest;

    /**
     * @var PreDispatchResult
    */
    private $preDispatchResult;

    public function __construct(RpcRequest $rpcRequest, PreDispatchResult $preDispatchResult)
    {
        $this->rpcRequest = $rpcRequest;
        $this->preDispatchResult = $preDispatchResult;
    }

    public function getPreDispatchResult()
    {
        return $this->preDispatchResult;
    }

    /**
     * @return RpcRequest
    */
    public function getRpcRequest()
    {
        return $this->rpcRequest;
    }

    /**
     * @param RpcRequest $rpcRequest
    */
    public function setRpcRequest(RpcRequest $rpcRequest)
    {
        $this->rpcRequest = $rpcRequest;
    }
}