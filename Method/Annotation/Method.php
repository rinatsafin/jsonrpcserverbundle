<?php

namespace TSCore\JsonRpcServerBundle\Method\Annotation;

/**
 * @Annotation
 */
class Method
{
    private $name;

    public function __construct($name)
    {
        $this->name = $name['value'];
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}