<?php

namespace Ddeboer\DocumentManipulationBundle\Document;

use Ddeboer\DocumentManipulationBundle\DocumentInterface;

interface DocumentFactoryInterface
{
    /**
     * Create a document from a file
     *
     * @param string $filename Filename
     *
     * @return \Ddeboer\DocumentManipulationBundle\DocumentInterface
     */
    function open($filename);

    /**
     * Create a document from a file body string
     *
     * @param string $string File contents
     *
     * @return DocumentInterface
     */
    function load($string);
}