<?php

namespace TSCore\JsonRpcServerBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use TSCore\JsonRpcServerBundle\Compiler\MethodTagCompiler;

class TSCoreJsonRpcServerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new MethodTagCompiler());
    }
}
