<?php

namespace TSCore\JsonRpcServerBundle\Request;

use TSCore\JsonRpcServerBundle\Request\Exception\FieldMissingException;

class RequestMapper
{
    /**
     * @param array() $requestArray
     * @return IRpcRequest
     * @throws FieldMissingException
    */
    public function tryMappedArrayToRpcRequest($requestArray)
    {
        if (!isset($requestArray['jsonrpc']))
            throw new FieldMissingException("Missing 'jsonrpc' field!");

        if (!isset($requestArray['method']))
            throw new FieldMissingException("Missing 'method' field!");

        if (!isset($requestArray['params']))
            throw new FieldMissingException("Missing 'params' field!");

        $rpcRequest = new RpcRequest($requestArray['jsonrpc'],
            $this->getMethodNameFromRpcMethod($requestArray['method']),
            $this->getActionNameFromRpcMethod($requestArray['method']),
            $requestArray['params'],
            isset($requestArray['id']) ? $requestArray['id'] : null
        );

        return $rpcRequest;
    }

    private function getMethodNameFromRpcMethod($method)
    {
        if (0 !== strpos($method, ".")) {
            $methodRes = explode(".", $method);
            return $methodRes[0];
        }

        return $method;
    }

    private function getActionNameFromRpcMethod($method)
    {
        if (0 !== strpos($method, ".")) {
            $methodRes = explode(".", $method);
            return $methodRes[1];
        }

        return null;
    }
}