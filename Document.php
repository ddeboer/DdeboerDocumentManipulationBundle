<?php

namespace Ddeboer\DocumentManipulationBundle;

use Symfony\Component\HttpFoundation\File\File;
use Ddeboer\DocumentManipulationBundle\Manipulator\ManipulatorCollection;

class Document implements DocumentInterface
{
    /**
     * @var File
     */
    private $file;

    private $type;

    const TYPE_DOC = 'doc';

    /**
     *
     * @param type $file
     * @param type $type
     *
     * @todo Use Symfony2’s File object instead
     */
    public function __construct($file, $type, ManipulatorCollection $manipulators)
    {
        $this->file = $file;
        $this->type = $type;
        $this->manipulators = $manipulators;
    }

    /**
     * @return \SplFileObject
     */
    public function getFile()
    {
        return $this->file;
    }

    public function getType()
    {
        return $this->type;
    }

    public function move($directory, $filename = null)
    {
        $this->file = $this->getFile()->move($directory, $filename);
    }

    public function isDoc()
    {
        return self::TYPE_DOC === $this->getType();
    }

    public function isPdf()
    {
        return self::TYPE_PDF === $this->getType();
    }

     function save($filename = null, $type = null)
     {
         
     }

    /**
     * {@inheritdoc}
     */
    function merge(DocumentDataInterface $data)
    {
        return $this->manipulators->findManipulator($this->type, 'merge')
            ->merge($this, $data);
    }

    /**
     * Append another document to this document
     *
     * @return DocumentInterface
     */
    function append(self $document)
    {

    }

    /**
     * Append multiple documents to this document
     *
     * @return DocumentInterface
     */
    function appendMultiple(array $documents)
    {

    }

    /**
     * Append this document to another document
     *
     * @return DocumentInterface
     */
    function appendTo(self $document)
    {

    }

    /**
     * Prepend another document to this document
     *
     * @return DocumentInterface
     */
    function prepend(self $document)
    {

    }

    /**
     * Prepend multiple documents to this document
     */
    function prependMultiple(array $documents)
    {

    }

    /**
     * Prepend this document to another document
     *
     * @return DocumentInterface
     */
    function prependTo(self $document)
    {

    }

    /**
     * Put this document in front of another document
     *
     * @return DocumentInterface $background
     * @param DocumentInterface $document   The background document
     */
    function putInFront(self $background)
    {

    }

    /**
     * Put this document behind another document
     *
     * @param DocumentInterface $foreground
     * @return DocumentInterface
     */
    function putBehind(self $foreground)
    {
        
    }
}