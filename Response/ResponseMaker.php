<?php

namespace TSCore\JsonRpcServerBundle\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseMaker
{
    public function makeJsonResponseFromRpcResponse(RpcResponse $rpcResponse)
    {
        $response = [
            'jsonrpc' => $rpcResponse->getJsonRpcVersion(),
            'id' => $rpcResponse->getId(),
            'result' => $rpcResponse->getResult()
        ];

        return new JsonResponse($response, $rpcResponse->getHttpCode());
    }

    public function makeJsonBatchResponseFromRpcResponseArray($rpcResponses)
    {
        if (!is_array($rpcResponses))
            throw new \InvalidArgumentException("rpcResponses must be array!");

        $responses = [];

        /** @var RpcResponse $rpcResponse */
        foreach ($rpcResponses as $rpcResponse) {
            $response = [
                'jsonrpc' => $rpcResponse->getJsonRpcVersion(),
                'id' => $rpcResponse->getId(),
                'result' => $rpcResponse->getResult()
            ];

            $responses[] = $response;
        }

        return new JsonResponse($responses, Response::HTTP_OK);
    }
}