<?php

namespace Ddeboer\DocumentManipulationBundle\Manipulator;

use Ddeboer\DocumentManipulationBundle\Manipulator\ManipulatorInterface;
use Symfony\Component\HttpFoundation\File\File;
use ZendService\LiveDocx\MailMerge;

/**
 * A manipulator that uses the LiveDocx web service to merge and convert
 * Doc and DocX files
 *
 * @author David de Boer <david@ddeboer.nl>
 */
class LiveDocxManipulator implements ManipulatorInterface
{
    protected $liveDocx;

    /**
     * Constructor
     *
     * @param MailMerge $liveDocx ZendService Mailmerge
     */
    public function __construct(MailMerge $liveDocx)
    {
        $this->liveDocx = $liveDocx;
    }

    /**
     * Mail merge a file
     *
     * @param File         $file   Symfony2 file object
     * @param \Traversable $data   Mail merge data
     * @param string       $format Output file format
     *
     * @return string File contents
     */
    public function merge(File $file, \Traversable $data, $format = 'pdf')
    {
        // Calculate MD5 hash for file.
        // LiveDocx seems to require a file extension, so add it.
        $hash = md5_file($file->getPathname()) . '.' . $file->getExtension();

        // Upload local template to server if it hasn't been uploaded yet
        if (!$this->liveDocx->templateExists($hash)) {
            $tmpFile = sys_get_temp_dir() . '/' . $hash;
            copy($file->getPathname(), $tmpFile);
            
            // Make sure file doesn't become writable only by www-data
            chmod($tmpFile, 0666 & ~umask());

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