<?php

namespace Ddeboer\DocumentManipulationBundle\Manipulator;

use Ddeboer\DocumentManipulationBundle\Manipulator\ManipulatorInterface;
use Ddeboer\DocumentManipulationBundle\File\File;
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
        // LiveDocx requires a file extension, otherwise it will return empty
        // white pages, so upload templates with their file extension.
        $hash = $file->getHashFilename();

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
            if ($value instanceof File) {
                // Image merge field
                $filename = $value->getHashFilename();
                if (!$this->liveDocx->imageExists($filename)) {
                    $tmpFile = \sys_get_temp_dir() . '/' . $filename;
                    \copy($value->getPathname(), $tmpFile);
                    $this->liveDocx->uploadImage($tmpFile);
                }

                $value = $filename;
            } elseif (\is_array($value) && empty($value)) {
                // To prevent Undefined offset: 0 in
                // ZendService/LiveDocx/MailMerge.php line 1175
                $value = null;
            }

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