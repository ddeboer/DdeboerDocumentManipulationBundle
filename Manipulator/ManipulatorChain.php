<?php

namespace Ddeboer\DocumentManipulationBundle\Manipulator;

use Ddeboer\DocumentManipulationBundle\Document\DocumentInterface;
use Ddeboer\DocumentManipulationBundle\Document\DocumentData;

/**
 * A chain of manipulators
 *
 * This chain implements the chain of command design pattern. Each manipulation
 * operation on documents should be called on this chain. This chain then
 * decides which manipulator to forward it to.
 *
 * @author David de Boer <david@ddeboer.nl>
 */
class ManipulatorChain
{
    protected $manipulators = array();

    /**
     * Constuctor
     *
     * @param array $manipulators ManipulatorInterface[]
     */
    public function __construct(array $manipulators = array())
    {
        foreach ($manipulators as $manipulator) {
            $this->add($manipulator);
        }
    }

    /**
     * Add a manipulator to the chain
     *
     * @param ManipulatorInterface $manipulator Manipulator
     */
    public function add(ManipulatorInterface $manipulator)
    {
        $this->manipulators[] = $manipulator;
    }

    /**
     * Find a manipulator in the chain that supports a certain document type
     * and operation
     *
     * @param string $type      Document type
     * @param string $operation Operation name
     *
     * @return ManipulatorInterface
     * @throws \Exception   If no manipulator can be found
     */
    public function findManipulator($type, $operation)
    {
        $filtered = array_filter(
            $this->manipulators,
            function($manipulator) use ($type, $operation) {
                return $manipulator->supports($type, $operation);
            }
        );

        if (0 === count($filtered)) {
            throw new \Exception(
                'No manipulator found for type ' . $type
                . ' and operation ' . $operation
            );
        }

        // For now, just return the first compatible manipulator that we find
        return reset($filtered);
    }

    /**
     * Mail merge a documents
     *
     * @param DocumentInterface     $document Document
     * @param DocumentDataInterface $data     Document data
     *
     * @return DocumentInterface
     */
    public function merge(DocumentInterface $document, DocumentData $data)
    {
        $contents = $this->findManipulator($document->getType(), 'merge')
            ->merge($document->getFile(), $data);

        $document = new Document($this);
        $document->setContents($contents);
        $document->setType(DocumentInterface::TYPE_PDF);

        return $document;
    }

    /**
     * Append one document to another
     *
     * @param DocumentInterface $document Document
     *
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

    /**
     * Append multiple documents to the original document
     *
     * @param DocumentInterface $document       Original document
     * @param array             $otherDocuments Array of documents to be appended
     *
     * @return DocumentInterface
     */
    public function appendMultiple(DocumentInterface $document, array $otherDocuments)
    {
        $outputFile = $this->findManipulator($document->getType(), 'appendMultiple')
            ->appendMultiple($document, $otherDocuments);

        $document = new Document($this);
        $document->setFile($outputFile);

        return $document;
    }

    public function layer(DocumentInterface $foreground, DocumentInterface $background)
    {
        $outputContents = $this->findManipulator($foreground->getType(), 'layer')
            ->layer($foreground, $background);

        $document = new Document($this);
        $document->setContents($outputContents);

        return $document;
    }
}

