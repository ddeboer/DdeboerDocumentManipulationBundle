<?php

namespace Ddeboer\DocumentManipulationBundle\Manipulator;

/**
 * Manipulates documents
 *
 * A manipulator should not know about documents and images; only about files.
 */
interface ManipulatorInterface
{
    /**
     * Does the manipulator support operation $operation on file type $type?
     *
     * @return boolean True if operation if supported, false otherwise
     */
    function supports($type, $operation);
}