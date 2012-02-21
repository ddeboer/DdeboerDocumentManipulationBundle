<?php

namespace Ddeboer\DocumentManipulationBundle\Manipulator;

use Zend\Service\LiveDocx\MailMerge;
use Symfony\Component\HttpFoundation\File\File;

class DocManipulator
{
    /**
     * @var MailMerge
     */
    private $liveDocx;

    public function getLiveDocx()
    {
        return $this->liveDocx;
    }

    public function setLiveDocx(MailMerge $liveDocx)
    {
        $this->liveDocx = $liveDocx;
    }

    public function merge(File $file, \Traversable $data, $outputFile, $format = 'pdf')
    {
        // Upload local template to server if it hasn't been uploaded yet
        if (!$this->liveDocx->templateExists($file->getFilename())) {
            $this->liveDocx->uploadTemplate($file->getPathname());
        }
        $this->liveDocx->setRemoteTemplate($file->getFilename());
//        $this->liveDocx->setLocalTemplate($file->getPathname());

        foreach ($data as $field => $value) {
            $this->liveDocx->assign($field, $value);
        }

        $this->liveDocx->createDocument();
        $document = $this->liveDocx->retrieveDocument($format);
        file_put_contents($outputFile, $document);

        return $outputFile;
    }
}