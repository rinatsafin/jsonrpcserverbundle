<?php

namespace TSCore\JsonRpcServerBundle\Exception;


class Exception extends \Exception
{
    private $context;

    public function __construct($message = "", $code = 0, $context = null, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    public function getContext()
    {
        return $this->context;
    }
}