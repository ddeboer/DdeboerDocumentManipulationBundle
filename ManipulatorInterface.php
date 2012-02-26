<?php

namespace Ddeboer\DocumentManipulationBundle;

interface ManipulatorInterface
{
    /**
     * @return boolean
     */
    function supports($mimeType);

    function concatenate($documents);

    function convertTo($type);

    function merge(DocumentData $data);
}