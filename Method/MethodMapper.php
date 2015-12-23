<?php

namespace TSCore\JsonRpcServerBundle\Method;

use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Annotations\FileCacheReader;
use TSCore\JsonRpcServerBundle\Exception\ActionNotFoundException;
use TSCore\JsonRpcServerBundle\Exception\DublicateActionException;
use TSCore\JsonRpcServerBundle\Method\Annotation\Action;

class MethodMapper
{
    /**
     * @var CachedReader
    */
    private $annotationReader;

    /**
     * @param CachedReader $annotationReader
    */
    public function __construct(CachedReader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    /**
     * @param IApiMethod $method
     * @param string $actionName
     * @return string
     * @throws DublicateActionException
     * @throws ActionNotFoundException
    */
    public function getClassMethodNameByMethodAndActionName(IApiMethod $method, $actionName)
    {
        $refClass = new \ReflectionClass(get_class($method));
        $classMethods = $refClass->getMethods();

        $classAction = null;
        foreach ($classMethods as $classMethod) {
            $annotationAction = $this->annotationReader->getMethodAnnotation($classMethod, '\TSCore\JsonRpcServerBundle\Method\Annotation\Action');
            if ($annotationAction instanceof Action) {
                if ($annotationAction->getName() === $actionName && $classAction !== null)
                    throw new DublicateActionException(sprintf("Action name %s dublicate in method %s", $actionName, get_class($method)));
                if ($annotationAction->getName() === $actionName && $classAction === null)
                    $classAction = $classMethod->getName();
            }
        }

        if (null === $classAction)
            throw new ActionNotFoundException(sprintf('Action %s not found in method %s!', $actionName, get_class($method)));

        return $classAction;
    }

    public function getMethodNameByClass($methodClass)
    {
        $refMethod = new \ReflectionClass($methodClass);

        $annotationMethod = $this->annotationReader->getClassAnnotation($refMethod, '\TSCore\JsonRpcServerBundle\Method\Annotation\Method');

        return $annotationMethod->getName();
    }
}