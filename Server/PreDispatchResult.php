<?php

namespace TSCore\JsonRpcServerBundle\Server;


class PreDispatchResult
{
    private $methodObject;

    private $action;

    public function __construct($methodObject, $action)
    {
        $this->methodObject = $methodObject;
        $this->action = $action;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return mixed
     */
    public function getMethodObject()
    {
        return $this->methodObject;
    }
}