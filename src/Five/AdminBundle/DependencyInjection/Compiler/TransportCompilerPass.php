<?php

namespace Five\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class TransportCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('five_admin.transport_chain')) {
            return;
        }

        $definition = $container->getDefinition(
            'five_admin.transport_chain'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'five_admin.transport'
        );
        //var_dump( $definition->getMethodCalls() );
        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                //var_dump( $attributes );
                $definition->addMethodCall(
                    'addTransport',
                    array(new Reference($id), $attributes)
                );
            }
        }
    }
}