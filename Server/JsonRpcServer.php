<?php

namespace TSCore\JsonRpcServerBundle\Server;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TSCore\JsonRpcServerBundle\Event\AfterMethodProcessingEvent;
use TSCore\JsonRpcServerBundle\Event\BeforeMethodProcessingEvent;
use TSCore\JsonRpcServerBundle\Exception\ApiException;
use TSCore\JsonRpcServerBundle\Exception\InvalidResponeException;
use TSCore\JsonRpcServerBundle\Parser\Exception\ParseException;
use TSCore\JsonRpcServerBundle\Parser\IParser;
use TSCore\JsonRpcServerBundle\Request\Exception\InvalidParamException;
use TSCore\JsonRpcServerBundle\Request\Exception\VersionNotSupportedException;
use TSCore\JsonRpcServerBundle\Request\IRpcRequest;
use TSCore\JsonRpcServerBundle\Request\RequestMapper;
use TSCore\JsonRpcServerBundle\Response\ResponseMaker;
use TSCore\JsonRpcServerBundle\Response\RpcResponse;

class JsonRpcServer
{
    /**
     * @var IDispatcher
    */
    private $dispatcher;

    /**
     * @var IParser
    */
    private $parser;

    /**
     * @var RequestMapper
    */
    private $requestMapper;

    /**
     * @var ResponseMaker
     */
    private $responseMaker;

    /**
     * @param IDispatcher $dispatcher
     * @param IParser $parser
     * @param RequestMapper $requestMapper
     * @param ResponseMaker $responseMaker
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(IDispatcher $dispatcher,
                                IParser $parser,
                                RequestMapper $requestMapper,
                                ResponseMaker $responseMaker,
                                EventDispatcherInterface $eventDispatcher = null)
    {
        $this->dispatcher = $dispatcher;
        $this->parser = $parser;
        $this->requestMapper = $requestMapper;
        $this->responseMaker = $responseMaker;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param string $context
     * @return array()
     * @throws ParseException
    */
    public function processing($context)
    {
        try {
            $dataAr = $this->parser->parse($context);
        } catch (ParseException $ex) {
            throw $ex;
        }

        if ($this->isBatch($dataAr)) {
            $results = [];
            foreach ($dataAr as $dataArItem) {
                /** @var IRpcRequest $rpcRequest */
                $rpcRequest = $this->requestMapper->tryMappedArrayToRpcRequest($dataArItem);

                $result = null;
                try {
                    $result = $this->handleRpcRequest($rpcRequest);
                } catch (ApiException $ex) {
                    $result = $this->responseMaker->makeJsonResponseFromRpcErrorResponse($ex, $rpcRequest);
                }

                $results[] = $result;
            }

            $responses = $this->responseMaker->makeJsonBatchResponseFromRpcResponseArray($results);

            return $responses;

        } else {
            /** @var IRpcRequest $rpcRequest */
            $rpcRequest = $this->requestMapper->tryMappedArrayToRpcRequest($dataAr);

            try {
                $result = $this->handleRpcRequest($rpcRequest);
                return $this->responseMaker->makeJsonResponseFromRpcResponse($result);
            } catch (ApiException $ex) {
                return $this->responseMaker->makeJsonResponseFromRpcErrorResponse($ex, $rpcRequest);
            }
        }
    }

    /**
     * @param IRpcRequest $rpcRequest
     * @return RpcResponse
     * @throws InvalidResponeException
    */
    private function handleRpcRequest(IRpcRequest $rpcRequest)
    {
        $this->validateRpcRequest($rpcRequest);

        $preDispatchResult = $this->dispatcher->preDispatch($rpcRequest->getMethod(), $rpcRequest->getAction());

        $methodActionCallable = $this->dispatcher->dispatch($rpcRequest->getMethod(), $rpcRequest->getAction());

        $rpcRequestUser = null;
        if (!is_null($this->eventDispatcher)) {
            /** @var BeforeMethodProcessingEvent $event */
            $event = $this->eventDispatcher->dispatch("jsonrpcserver.before_method_processing", new BeforeMethodProcessingEvent($rpcRequest, $preDispatchResult));
            $rpcRequestUser = $event->getRpcRequest();
        }
        if ($rpcRequestUser == null)
            $rpcRequestUser = $rpcRequest;

        $rpcResponse = call_user_func($methodActionCallable, $rpcRequestUser);

        if (!is_null($this->eventDispatcher)) {
            /** @var AfterMethodProcessingEvent $event */
            $event = $this->eventDispatcher->dispatch("jsonrpcserver.after_method_processing", new AfterMethodProcessingEvent($rpcResponse, $rpcRequest->getId()));
            $rpcResponse = $event->getResponse();
        }

        if (!($rpcResponse instanceof RpcResponse))
            throw new InvalidResponeException(sprintf("Response should be object of %s class", RpcResponse::class));

        return $rpcResponse;
    }

    /**
     * @param IRpcRequest $rpcRequest
     * @return void
     * @throws VersionNotSupportedException
     * @throws InvalidParamException
    */
    private function validateRpcRequest(IRpcRequest $rpcRequest)
    {
        if ($rpcRequest->getJsonRpcVersion() !== '2.0')
            throw new VersionNotSupportedException("JsonRpcServer support only 2.0 specification!");

        if (!is_array($rpcRequest->getParams()))
            throw new InvalidParamException("Params must be array!");
    }

    /**
     * @param array $dataAr
     * @return boolean
     * @throws \InvalidArgumentException
    */
    private function isBatch(array $dataAr)
    {
        if (!is_array($dataAr))
            throw new \InvalidArgumentException("Argument must be array");

        if (isset($dataAr[0]["jsonrpc"]))
            return true;

        return false;
    }
}