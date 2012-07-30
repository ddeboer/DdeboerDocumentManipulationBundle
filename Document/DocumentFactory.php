<?php

namespace Ddeboer\DocumentManipulationBundle\Document;

use Symfony\Component\HttpFoundation\File\File;
use Ddeboer\DocumentManipulationBundle\Manipulator\ManipulatorChain;

/**
 * Creates a document and passes the manipulator chain to it
 *
 */
class DocumentFactory implements DocumentFactoryInterface
{
    protected $manipulators = array();

    /**
     * Constructor
     *
     * @param ManipulatorChain $manipulators Manipulator chain
     */
    public function __construct(ManipulatorChain $manipulators)
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
}