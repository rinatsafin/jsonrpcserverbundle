<?php

namespace TSCore\JsonRpcServerBundle\Response;


class RpcErrorResponse
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
     * @var integer
    */
    private $errorCode;

    /**
     * @var string
    */
    private $errorMessage;


    public function __construct($errorCode, $errorMessage, $id = null)
    {
        $this->id = $id;
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
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
     * @return int
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
}