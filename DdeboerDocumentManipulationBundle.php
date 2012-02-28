<?php

namespace Ddeboer\DocumentManipulationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ddeboer\DocumentManipulationBundle\DependencyInjection\Compiler\ManipulatorPass;

class DdeboerDocumentManipulationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ManipulatorPass());
    }
}
