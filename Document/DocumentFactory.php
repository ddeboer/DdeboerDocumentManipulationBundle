<?php

namespace Ddeboer\DocumentManipulationBundle\Document;

use Ddeboer\DocumentManipulationBundle\File\File;
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
        return new Document($this->manipulators, File::fromFilename($filename));
    }

    /**
     * {@inheritdoc}
     */
    public function load($string)
    {
        return new Document($this->manipulators, File::fromString($string));
    }
}