<?php

namespace Ddeboer\DocumentManipulationBundle\Document;

use Ddeboer\DocumentManipulationBundle\Manipulator\ManipulatorChain;
use Ddeboer\DocumentManipulationBundle\File\File;

/**
 * {@inheritdoc}
*/
class Document implements DocumentInterface
{
    protected $type;

    /**
     * Constructor
     *
     * For easy construction, use the DocumentFactory.
     *
     * @param ManipulatorChain $manipulators Chain of manipulators
     * @param File             $file         File
     */
    public function __construct(ManipulatorChain $manipulators, File $file)
    {
        $this->manipulators = $manipulators;
        $this->file = $file;
    }

    /**
     *
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        switch ($this->file->getMimeType()) {
            case 'application/pdf':
                return self::TYPE_PDF;

            case 'application/msword':
                if ('docx' == $this->file->getExtension()) {
                    return self::TYPE_DOCX;
                }
                return self::TYPE_DOC;

            default:
                break;
        }

        return $this->type;
    }

    public function move($directory, $filename = null)
    {
        $this->file = $this->getFile()->move($directory, $filename);
    }

    public function isDoc()
    {
        return DocumentInterface::TYPE_DOC === $this->getType();
    }

    public function isPdf()
    {
        return DocumentInterface::TYPE_PDF === $this->getType();
    }

    /**
     * {@inheritdoc}
     */
    public function save($filename = null)
    {
        if (!$filename) {
            return $this->move(sys_get_temp_dir());
        } else {
            return $this->move(\dirname($filename), \basename($filename));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function merge(DocumentData $data)
    {
        return $this->manipulators->merge($this, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function append(DocumentInterface $document)
    {
        return $this->manipulators->append($this, $document);
    }

    /**
     * {@inheritdoc}
     */
    public function appendMultiple(array $documents)
    {
        return $this->manipulators->appendMultiple($this, $documents);
    }

    /**
     * Append this document to another document
     *
     * @return DocumentInterface
     */
    public function appendTo(DocumentInterface $document)
    {
        throw new \Exception('Not yet implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(DocumentInterface $document)
    {
        throw new \Exception('Not yet implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function prependMultiple(array $documents)
    {
        throw new \Exception('Not yet implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function prependTo(DocumentInterface $document)
    {
        throw new \Exception('Not yet implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function putInFront(DocumentInterface $background)
    {
        return $this->manipulators->layer($this, $background);
    }

    /**
     * {@inheritdoc}
     */
    public function putBehind(DocumentInterface $foreground)
    {
        throw new \Exception('Not yet implemented');
    }
}