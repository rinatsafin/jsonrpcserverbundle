<?php


namespace TSCore\JsonRpcServerBundle\Parser;


interface IParser
{
    public function parse($data);
}