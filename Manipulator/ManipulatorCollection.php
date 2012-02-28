<?php

namespace Ddeboer\DocumentManipulationBundle\Manipulator;

use Ddeboer\DocumentManipulationBundle\ManipulatorInterface;
use Ddeboer\DocumentManipulationBundle\Document;
use Ddeboer\DocumentManipulationBundle\DocumentInterface;
use Ddeboer\DocumentManipulationBundle\DocumentDataInterface;

/**
 * A collection of manipulators
 *
 * @author David de Boer <david@ddeboer.nl>
 */
class ManipulatorCollection
{
    protected $manipulators = array();

    public function __construct(array $manipulators = array())
    {
        foreach ($manipulators as $manipulator) {
            $this->add($manipulator);
        }
    }

    public function add(ManipulatorInterface $manipulator)
    {
        $this->manipulators[] = $manipulator;
    }

    /**
     * Find a manipulator that supports a certain document type and operation
     *
     * @param string $type
     * @param string $operation
     * @return ManipulatorInterface
     * @throws \Exception   If no manipulator can be found
     */
    public function findManipulator($type, $operation)
    {
        $filtered = array_filter($this->manipulators, function($manipulator) use ($type, $operation) {
            return $manipulator->supports($type, $operation);
        });

        if (0 === count($filtered)) {
            throw new \Exception('No manipulator found for type ' . $type
                . ' and operation ' . $operation);
        }

        // For now, just return the first compatible manipulator that we find
        return reset($filtered);
    }

    /**
     * @param DocumentInterface $document
     * @param DocumentDataInterface $data
     * @return DocumentInterface
     */
    public function merge(DocumentInterface $document, DocumentDataInterface $data)
    {
        $contents = $this->findManipulator($document->getType(), 'merge')
            ->merge($document->getFile(), $data);

        $document = new Document($this);
        $document->setContents($contents);
        $document->setType(DocumentInterface::TYPE_PDF);
        
        return $document;
    }

    /**
     *
     * @param DocumentInterface $document
     * @return DocumentInterface
     */
    public function append(DocumentInterface $document1, DocumentInterface $document2)
    {
        $outputFile = $this->findManipulator($document1->getType(), 'append')
            ->append($document1, $document2);

        $document = new Document($this);
        $document->setFile($outputFile);

        return $document;
    }
}