<?php
namespace Ddeboer\DocumentManipulationBundle\Exception;

class ManipulatorNotFoundException extends \RuntimeException
{
    public function __construct($type, $operation)
    {
        $message = sprintf(
            'No manipulator found for document type %s and operation %s',
            $type,
            $operation
        );

        parent::__construct($message);
    }
}
