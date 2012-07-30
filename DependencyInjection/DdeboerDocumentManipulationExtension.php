<?php

namespace Ddeboer\DocumentManipulationBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DdeboerDocumentManipulationExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $container->setParameter(
            'ddeboer_document_manipulation.pdftk.binary',
            $config['pdftk']['binary']
        );

        if (isset($config['livedocx'])) {
            $liveDocx = $this->addLiveDocx($config['livedocx']);
            $container->setDefinition(
                'ddeboer_document.manipulation.manipulator.live_docx.mail_merge',
                $liveDocx
            );

            $container
                ->getDefinition('ddeboer_document_manipulation.manipulator.live_docx')
                ->setAbstract(false)
                ->addArgument(new Reference('ddeboer_document.manipulation.manipulator.live_docx.mail_merge'));
        }
    }

    private function addLiveDocx(array $config)
    {
        $definition = new Definition('Zend\Service\LiveDocx\MailMerge');
        $definition->addMethodCall('setUsername', array($config['username']));
        $definition->addMethodCall('setPassword', array($config['password']));
        $definition->addMethodCall('setWsdl', array($config['wsdl']));

        return $definition;
    }
}
