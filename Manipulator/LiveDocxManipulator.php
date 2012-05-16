<?php

namespace Ddeboer\DocumentManipulationBundle\Manipulator;

use Ddeboer\DocumentManipulationBundle\DocumentInterface;
use Ddeboer\DocumentManipulationBundle\DocumentDataInterface;
use Ddeboer\DocumentManipulationBundle\ManipulatorInterface;
use Symfony\Component\HttpFoundation\File\File;
use Zend\Service\LiveDocx\MailMerge;

/**
 * A manipulator that uses the LiveDocx web service to merge and convert
 * Doc and DocX files
 *
 * @author David de Boer <david@ddeboer.nl>
 */
class LiveDocxManipulator implements ManipulatorInterface
{
    protected $liveDocx;

    public function __construct(MailMerge $liveDocx)
    {
        $this->liveDocx = $liveDocx;
    }

    /**
     * @param DocumentInterface $document
     * @param DocumentDataInterface $data
     * @param type $format
     * @return type
     */
    public function merge(File $file, \Traversable $data, $format = 'pdf')
    {
        // Calculate MD5 hash for file
        // LiveDocx seems to require a file extension
        $hash = md5_file($file->getPathname()) . '.' . $file->getExtension();
        $tmpFile = sys_get_temp_dir() . '/' . $hash;
        copy($file->getPathname(), $tmpFile);

        // Make sure file doesn't become writable only by www-data
        @chmod($target, 0666 & ~umask());

        // Upload local template to server if it hasn't been uploaded yet
        if (!$this->liveDocx->templateExists($hash)) {
            $this->liveDocx->uploadTemplate($tmpFile);
        }

        $this->liveDocx->setRemoteTemplate($hash);

        foreach ($data as $field => $value) {
            $this->liveDocx->assign($field, $value);
        }

        $this->liveDocx->createDocument();
        $contents = $this->liveDocx->retrieveDocument($format);
        return $contents;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($type, $operation)
    {
        if (in_array($type, array('doc', 'docx'))
            && in_array($operation, array('merge', 'convert'))) {
            return true;
        }

        return false;
    }
}