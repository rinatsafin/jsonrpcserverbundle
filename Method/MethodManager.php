<?php

namespace TSCore\JsonRpcServerBundle\Method;

use TSCore\JsonRpcServerBundle\Exception\MethodNotFoundException;
use TSCore\JsonRpcServerBundle\Method\Exception\MethodAlreadyExistsException;

class MethodManager
{
    /**
     * @var array()
    */
    private $methods;

    /**
     * @param string $name
     * @param IApiMethod $method
     * @return MethodManager
     * @throws MethodAlreadyExistsException
    */
    public function addMethod($name, IApiMethod $method)
    {
        $name = (string)$name;

        if (isset($this->methods[$name]))
            throw new MethodAlreadyExistsException(sprintf("Method '%s' already exists!", $name));

        $this->methods[$name] = $method;

        return $this;
    }

    /**
     * @param string $methodName
     * @return boolean
    */
    public function isMethodExists($methodName)
    {
        $methodName = (string)$methodName;

        return isset($this->methods[$methodName]);
    }

    /**
     * @param string $methodName
     * @return IApiMethod
     * @throws MethodNotFoundException
    */
    public function getMethodByName($methodName)
    {
        $methodName = (string)$methodName;

        if (!$this->isMethodExists($methodName))
            throw new MethodNotFoundException(sprintf("Method with name '%s' not found!", $methodName));

        return $this->methods[$methodName];
    }
}