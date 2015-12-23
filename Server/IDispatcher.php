<?php

namespace TSCore\JsonRpcServerBundle\Server;


interface IDispatcher
{
    /**
     * @param string $methodName
     * @param string $actionName
    */
    public function dispatch($methodName, $actionName);

    /**
     * @param string $methodName
     * @param string $actionName
     * @return PreDispatchResult
     */
    public function preDispatch($methodName, $actionName);
}