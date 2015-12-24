<?php

namespace TSCore\JsonRpcServerBundle\Request;


class RpcRequest implements IRpcRequest
{
    private $jsonRpcVersion;

    private $id;

    private $method;

    private $action;

    private $params;

    /**
     * @param string $jsonRpcVersion
     * @param string $method
     * @param string $action
     * @param array $params
     * @param string $id
    */
    public function __construct($jsonRpcVersion, $method, $action, $params, $id = null)
    {
        $this->jsonRpcVersion = $jsonRpcVersion;
        $this->id = $id;
        $this->method = $method;
        $this->action = $action;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getJsonRpcVersion()
    {
        return $this->jsonRpcVersion;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
}