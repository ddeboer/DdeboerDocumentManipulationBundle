<?php

namespace Ddeboer\DocumentManipulationBundle;

use Ddeboer\DocumentManipulationBundle\DocumentInterface;

interface DocumentFactoryInterface
{
    /**
     * @return \Ddeboer\DocumentManipulationBundle\DocumentInterface
     */
    function open($filename);

    /**
     * @return DocumentInterface
     */
    function load($string, $type = null);
}