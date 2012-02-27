<?php

namespace Ddeboer\DocumentManipulationBundle;

use Symfony\Component\HttpFoundation\File\File;
use Ddeboer\DocumentManipulationBundle\Manipulator\ManipulatorCollection;

class DocumentFactory implements DocumentFactoryInterface
{
    protected $manipulators = array();

    public function __construct(ManipulatorCollection $manipulators)
    {
        $this->manipulators = $manipulators;
    }

    /**
     * {@inheritdoc}
     */
    public function open($filename)
    {
        $file = new File($filename);
        switch ($file->getMimeType()) {
            case 'application/pdf':
                return new Document($file, DocumentInterface::TYPE_PDF, $this->manipulators);

                break;

            default:
                break;
        }

        switch ($file->getExtension()) {
            case 'docx':
                return new Document($file, DocumentInterface::TYPE_DOCX, $this->manipulators);

                break;

            default:
                break;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function load($string, $type)
    {
        $filename = $this->createTempfile($string);
        return $this->open($filename);
    }

    /**
     * @return string Filename
     */
    protected function createTempfile($contents = null)
    {
        $filename = tempnam('/tmp', 'doc_');
        if ($contents) {
            file_put_contents($filename, $contents);
        }

        return $filename;
    }
}