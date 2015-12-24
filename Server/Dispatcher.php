<?php

namespace TSCore\JsonRpcServerBundle\Server;

use TSCore\JsonRpcServerBundle\Method\IApiMethod;
use TSCore\JsonRpcServerBundle\Method\MethodManager;
use TSCore\JsonRpcServerBundle\Method\MethodMapper;
use TSCore\JsonRpcServerBundle\Request\IRpcRequest;

class Dispatcher implements IDispatcher
{
    /**
     * @var MethodManager
    */
    private $methodManager;

    /**
     * @var MethodMapper
    */
    private $methodMapper;


    /**
     * @param MethodMapper $methodMapper
     * @param MethodManager $methodManager
    */
    public function __construct(MethodMapper $methodMapper, MethodManager $methodManager)
    {
        $this->methodMapper = $methodMapper;
        $this->methodManager = $methodManager;
    }

    public function preDispatch($methodName, $actionName)
    {
        $methodName = strval($methodName);
        $actionName = strval($actionName);

        $methodObject = $this->methodManager->getMethodByName($methodName);
        $action = $this->methodMapper->getClassMethodNameByMethodAndActionName($methodObject, $actionName);

        return new PreDispatchResult($methodObject, $action);
    }

    public function dispatch($methodName, $actionName)
    {
        $methodName = strval($methodName);
        $actionName = strval($actionName);

        $methodObject = $this->methodManager->getMethodByName($methodName);
        $action = $this->methodMapper->getClassMethodNameByMethodAndActionName($methodObject, $actionName);

        return function(IRpcRequest $rpcRequest) use ($methodObject, $action) {
            return $this->invoke($methodObject, $action, $rpcRequest);
        };
    }

    private function invoke(IApiMethod $method, $actionName, IRpcRequest $rpcRequest)
    {
        return $method->$actionName($rpcRequest);
    }
}