<?php

namespace TSCore\JsonRpcServerBundle\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class MethodTagCompiler implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $serviceName = 'jsonrpcserver.method_manager';
        $tagName = 'jsonrpcserver.method';

        if (!$container->has($serviceName)) return;

        $definition = $container->findDefinition($serviceName);

        $taggedServices = $container->findTaggedServiceIds($tagName);

        $methodMapper = $container->get('jsonrpcserver.method_mapper');

        foreach ($taggedServices as $id => $tags)
        {
            $methodServiceDefinition = $container->findDefinition($id);
            $methodName = $methodMapper->getMethodNameByClass($methodServiceDefinition->getClass());

            $definition->addMethodCall('addMethod', [$methodName, new Reference($id)]);
        }
    }
}