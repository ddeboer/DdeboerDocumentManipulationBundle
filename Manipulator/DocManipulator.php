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
        // Calculate MD5 hash for file; the template must have an extension,
        // or LiveDocx will be confused.
        $hash = md5_file($file->getPathname()) . '.' . $file->getExtension();

        $tmpFile = sys_get_temp_dir() . '/' . $hash;
        copy($file->getPathname(), $tmpFile);

        // Upload local template to server if it hasn't been uploaded yet
        if (!$this->liveDocx->templateExists($hash)) {
            $this->liveDocx->uploadTemplate($tmpFile);
        }

        $this->liveDocx->setRemoteTemplate($hash);

        foreach ($data as $field => $value) {
            $this->liveDocx->assign($field, $value);
        }

        $this->liveDocx->createDocument();
        $document = $this->liveDocx->retrieveDocument($format);
        file_put_contents($outputFile, $document);

        return $outputFile;
    }
}