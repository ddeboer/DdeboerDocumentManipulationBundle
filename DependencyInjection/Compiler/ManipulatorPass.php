<?php

namespace Ddeboer\DocumentManipulationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ManipulatorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('ddeboer_document_manipulation.manipulator_chain')) {
            $collection = $container->getDefinition('ddeboer_document_manipulation.manipulator_chain');
            $manipulators = $container->findTaggedServiceIds('ddeboer_document_manipulation.manipulator');
            foreach ($manipulators as $id => $attributes) {
                if (!$container->getDefinition($id)->isAbstract()) {
                    $collection->addMethodCall('add', array(new Reference($id)));
                }
            }
        }
    }
}