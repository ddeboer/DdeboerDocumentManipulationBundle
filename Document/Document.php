<?php

namespace Ddeboer\DocumentManipulationBundle\Document;

use Symfony\Component\HttpFoundation\File\File;
use Ddeboer\DocumentManipulationBundle\Manipulator\ManipulatorChain;

/**
 * {@inheritdoc}
*/
class Document implements DocumentInterface
{
    /**
     * @var File
     */
    protected $file;

    protected $contents;

    protected $type;

    /**
     * Constructor
     *
     * For easy construction, use the DocumentFactory.
     *
     * @param ManipulatorChain $manipulators Chain of manipulators
     */
    public function __construct(ManipulatorChain $manipulators)
    {
        $this->manipulators = $manipulators;
    }

    public function setFile(File $file)
    {
        $this->file = $file;

        // First try to guess by extension, because MIME type is not guessed
        // correctly for .docx files using $file->getMimeType()
        switch ($file->getExtension()) {
            case 'docx':
                $this->setType(DocumentInterface::TYPE_DOCX);
                return $this;
            default:

        }

        switch ($file->getMimeType()) {
            case 'application/pdf':
                $this->setType(DocumentInterface::TYPE_PDF);
                return $this;
            case 'application/msword':
                $this->file = $file;
                $this->setType(DocumentInterface::TYPE_DOC);
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

        // Guess type from contents
        $finfo = new \finfo(\FILEINFO_MIME);
        if (1 === preg_match('/^(.*);/', $finfo->buffer($contents), $matches)) {
            switch ($matches[1]) {
                case 'application/pdf':
                    $this->setType(DocumentInterface::TYPE_PDF);
                    return;
                case 'application/msword':
                    $this->setType(DocumentInterface::TYPE_DOC);
                    return;
                default:
                    $this->setType(DocumentInterface::TYPE_DOCX);
            }
        }
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
            $filename = $this->createTempfile();
        }

        file_put_contents($filename, $this->getContents());
        $this->setFile(new File($filename));
        die('ok');

        return $this;
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

    protected function createTempfile()
    {
        $name = md5(microtime());

        // Add file extension, if available
        if ($this->getType()) {
            $name .= '.' . $this->getType();
        }

        return sys_get_temp_dir() . '/' . $name;
    }
}