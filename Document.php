<?php

namespace Ddeboer\DocumentManipulationBundle;

use Symfony\Component\HttpFoundation\File\File;

class Document
{
    /**
     * @var File
     */
    private $file;

    private $type;

    const TYPE_PDF = 'pdf';
    const TYPE_DOC = 'doc';

    /**
     *
     * @param type $file
     * @param type $type
     *
     * @todo Use Symfony2’s File object instead
     */
    public function __construct($file, $type = null)
    {
        if (!$file instanceof File) {
            $file = new File($file);
        }

        $this->file = $file;

        if (!$type) {
            switch ($file->getMimeType()) {
                case 'application/pdf':
                    $this->type = self::TYPE_PDF;
                    break;

                default:
                    break;
            }
        }
        $this->type = $type;
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
}