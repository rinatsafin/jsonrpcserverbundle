<?php


namespace TSCore\JsonRpcServerBundle\Request;


interface IRpcRequest
{
    public function getJsonRpcVersion();
    public function getId();
    public function getMethod();
    public function getAction();
    public function getParams();
}