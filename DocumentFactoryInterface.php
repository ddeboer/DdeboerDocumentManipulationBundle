<?php

namespace Ddeboer\DocumentManipulationBundle;

interface DocumentFactoryInterface
{
    /**
     * @return DocumentInterface
     */
    function open($filename);

    /**
     * @return DocumentInterface
     */
    function load($string);
}