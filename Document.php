<?php

namespace Ddeboer\DocumentManipulationBundle;

use Symfony\Component\HttpFoundation\File\File;
use Ddeboer\DocumentManipulationBundle\Manipulator\ManipulatorCollection;

class Document implements DocumentInterface
{
    /**
     * @var File
     */
    protected $file;

    protected $contents;

    protected $type;
    
    public function __construct(ManipulatorCollection $manipulators)
    {
        $this->manipulators = $manipulators;
    }

    public function setFile(File $file)
    {
        switch ($file->getMimeType()) {
            case 'application/pdf':                
                $this->file = $file;
                $this->setType(DocumentInterface::TYPE_PDF);
                return $this;                
            case 'application/msword':
                $this->file = $file;
                $this->setType(DocumentInterface::TYPE_DOC);
                return $this;

            default:
                break;
        }

        switch ($file->getExtension()) {
            case 'docx':
                $this->file = $file;
                $this->setType(DocumentInterface::TYPE_DOCX);
                return $this;

            default:
                break;
        }
    }

    /**
     * @return \SplFileObject
     */
    public function getFile()
    {
        if (null === $this->file && $this->contents) {
            $filename = $this->createTempfile();
            file_put_contents($filename, $this->contents);
            $this->file = new File($filename);
        }

        return $this->file;
    }

    public function getContents()
    {
        if (!$this->contents && $this->file) {
            $this->contents = file_get_contents($this->file->getPathname());
        }

        return $this->contents;
    }

    public function setContents($contents)
    {
        $this->contents = $contents;
    }

    public function setType($type)
    {
        $this->type = $type;
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

    /**
     * {@inheritdoc}
     */
    public function save($filename = null)
    {
        if (!$filename) {
            $filename = $this->createTempfile();
        }

        file_put_contents($filename, $this->getContents());
        $this->setFile(new File($filename));

        return $this;
    }

    /**
     * @return DocumentInterface
     */
    function merge(DocumentDataInterface $data)
    {
        return $this->manipulators->merge($this, $data);
    }

    /**
     * Append another document to this document
     *
     * @return DocumentInterface
     */
    public function append(DocumentInterface $document)
    {
        return $this->manipulators->append($this, $document);
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

    protected function createTempfile()
    {
        $filename = tempnam('/tmp', 'doc_');
        return $filename;
    }

}