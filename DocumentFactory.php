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
        $document = new Document($this->manipulators);
        $document->setFile($file);
        return $document;
    }

    /**
     * {@inheritdoc}
     */
    public function load($string, $type = null)
    {
        $document = new Document($this->manipulators);
        $document->setContents($string);
        if ($type) {
            $document->setType($type);
        }
        return $document;
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