<?php

namespace Ddeboer\DocumentManipulationBundle\Manipulator;

use Ddeboer\DocumentManipulationBundle\DocumentInterface;
use Ddeboer\DocumentManipulationBundle\DocumentDataInterface;
use Ddeboer\DocumentManipulationBundle\ManipulatorInterface;
use Zend\Service\LiveDocx\MailMerge;

class LiveDocxManipulator implements ManipulatorInterface
{
    protected $liveDocx;

    public function __construct(MailMerge $liveDocx)
    {
        $this->liveDocx = $liveDocx;
    }

    public function merge(DocumentInterface $document, DocumentDataInterface $data, $format = 'pdf')
    {
        $file = $document->getFile();
        // Calculate MD5 hash for file
        $hash = md5_file($file->getPathname());
        $tmpFile = sys_get_temp_dir() . '/' . $hash;
        copy($file->getPathname(), $tmpFile);

        // Upload local template to server if it hasn't been uploaded yet
        if (!$this->liveDocx->templateExists($hash)) {
            $this->liveDocx->uploadTemplate($tmpFile);
        }

        $this->liveDocx->setLocalTemplate($file->getPathname());

        foreach ($data as $field => $value) {
            $this->liveDocx->assign($field, $value);
        }

        $this->liveDocx->createDocument();
        $document = $this->liveDocx->retrieveDocument($format);
        file_put_contents($outputFile, $document);

        return $outputFile;
    }

    public function supports($type)
    {
        if ($type === 'doc' || $type === 'docx') {
            return true;
        }

        return false;
    }
}