<?php
namespace Ddeboer\DocumentManipulationBundle\Exception;

use Ddeboer\DocumentManipulationBundle\File\File;

class InvalidDocumentException extends \RuntimeException
{
    public function __construct(File $file, \Exception $previous = null)
    {
        $message = sprintf(
            'File %s with mime type %s is an invalid document',
            $file->getPathname(),
            $file->getMimeType()
        );

        parent::__construct($message, 0, $previous);
    }
}
