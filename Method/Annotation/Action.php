<?php

namespace TSCore\JsonRpcServerBundle\Method\Annotation;

/**
 * @Annotation
 */
class Action
{
    public $name;

    public function getName()
    {
        return $this->name;
    }
}