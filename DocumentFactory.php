<?php

namespace Ddeboer\DocumentManipulationBundle;

use Ddeboer\Manipulator;
use Symfony\Component\HttpFoundation\File\File;

class DocumentFactory implements DocumentFactoryInterface
{
    protected $manipulators = array();

    public function __construct(array $manipulators)
    {
        $this->manpulators = $manipulators;
    }

    /**
     * {@inheritdoc}
     */
    public function open($filename)
    {
        $file = new File($filename);
        switch ($file->getMimeType()) {
            case 'application/pdf':
                return new Document($file, $this->pdfManipulator);

                break;

            default:
                break;

            throw new \Exception('unknown type ' . $file->getMimeType());
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