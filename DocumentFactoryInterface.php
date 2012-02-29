<?php

namespace Ddeboer\DocumentManipulationBundle;

use Ddeboer\DocumentManipulationBundle\DocumentInterface;

interface DocumentFactoryInterface
{
    /**
     * Create a document from a file
     *
     * @return \Ddeboer\DocumentManipulationBundle\DocumentInterface
     */
    function open($filename);

    /**
     * Create a document from a file body string
     *
     * @return DocumentInterface
     */
    function load($string, $type = null);
}