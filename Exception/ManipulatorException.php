<?php
namespace Ddeboer\DocumentManipulationBundle\Exception;

class ManipulatorException extends \RuntimeException
{
    public function __construct($manipulator, $operation, $previous)
    {
        $message = sprintf(
            'Manipulator %s threw exception during operation %s',
            $manipulator,
            $operation
        );

        parent::__construct($message, 0, $previous);
    }
}
