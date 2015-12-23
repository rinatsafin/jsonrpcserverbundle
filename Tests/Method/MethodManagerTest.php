<?php

namespace TSCore\JsonRpcServerBundle\Tests\Method;

use TSCore\JsonRpcServerBundle\Method\MethodManager;

class MethodManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testaddMethod()
    {
        $methodName = "some_name";
        $method = $this->getMock('TSCore\JsonRpcServerBundle\Method\IApiMethod');

        $manager = new MethodManager();
        $manager->addMethod($methodName, $method);

        $this->assertEquals($method, $manager->getMethodByName($methodName));
    }

    /**
     * @expectedException \TSCore\JsonRpcServerBundle\Method\Exception\MethodAlreadyExistsException
    */
    public function testaddMethodAlreadyExistsMethod()
    {
        $methodName_1 = "some_name_1";
        $method_1 = $this->getMock('TSCore\JsonRpcServerBundle\Method\IApiMethod');

        $manager = new MethodManager();
        $manager->addMethod($methodName_1, $method_1);
        $manager->addMethod($methodName_1, $method_1);
    }

    public function testifExistsMethodTrue()
    {
        $methodName = "some_name";
        $method = $this->getMock('TSCore\JsonRpcServerBundle\Method\IApiMethod');

        $manager = new MethodManager();
        $manager->addMethod($methodName, $method);

        $this->assertTrue($manager->isMethodExists($methodName));
    }

    public function testifExistsMethodFalse()
    {
        $methodName = "some_name";
        $manager = new MethodManager();
        $this->assertFalse($manager->isMethodExists($methodName));
    }

    public function testgetMethodByName()
    {
        $methodName = "some_name";
        $method = $this->getMock('TSCore\JsonRpcServerBundle\Method\IApiMethod');

        $manager = new MethodManager();
        $manager->addMethod($methodName, $method);

        $actualMethod = $manager->getMethodByName($methodName);

        $this->assertEquals($method, $actualMethod);
    }

    /**
     * @expectedException \TSCore\JsonRpcServerBundle\Exception\MethodNotFoundException
    */
    public function testgetMethodByNameNegative()
    {
        $manager = new MethodManager();
        $manager->getMethodByName("123");
    }

    /**
     * @expectedException \TSCore\JsonRpcServerBundle\Exception\MethodNotFoundException
     */
    public function testgetMethodByNameNegative2()
    {
        $manager = new MethodManager();
        $method = $this->getMock('TSCore\JsonRpcServerBundle\Method\IApiMethod');
        $manager->addMethod("asd", $method);
        $manager->getMethodByName("123");
    }
}