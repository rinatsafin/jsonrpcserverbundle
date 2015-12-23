<?php

namespace TSCore\JsonRpcServerBundle\Response;


class RpcResponse
{
    /**
     * @var string
    */
    private $jsonRpcVersion = '2.0';

    /**
     * @var string
    */
    private $id;

    /**
     * @var array()
    */
    private $result = [];

    /**
     * @var integer
    */
    private $httpCode = 200;


    /**
     * @param string $id
     * @param array() $result
     * @throws \InvalidArgumentException
    */
    public function __construct($id, $result = [])
    {
        if (!is_array($result))
            throw new \InvalidArgumentException("Result must be array!");

        $this->id = (string)$id;
        $this->result = $result;
    }

    /**
     * @param int $httpCode
     * @return RpcResponse
    */
    public function setHttpCode($httpCode)
    {
        $this->httpCode = intval($httpCode);

        return $this;
    }

    /**
     * @return int
    */
    public function getHttpCode()
    {
        return $this->httpCode;
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
     * @return array()
     */
    public function getResult()
    {
        return $this->result;
    }
}