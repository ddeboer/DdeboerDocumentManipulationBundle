<?php

namespace Ddeboer\DocumentManipulationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ddeboer_document_manipulation');

        $rootNode
            ->children()
                ->arrayNode('livedocx')
                    ->children()
                        ->scalarNode('username')->isRequired()->end()
                        ->scalarNode('password')->isRequired()->end()
                        ->scalarNode('wsdl')->defaultValue('https://api.livedocx.com/2.1/mailmerge.asmx?wsdl')->end()
                    ->end()
                ->end()
                ->arrayNode('pdftk')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('binary')->defaultValue('pdftk')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
