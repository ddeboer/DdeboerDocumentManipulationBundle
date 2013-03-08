<?php

namespace Ddeboer\DocumentManipulationBundle\Manipulator;

use Ddeboer\DocumentManipulationBundle\Manipulator\ManipulatorInterface;
use Ddeboer\DocumentManipulationBundle\File\File;
use Ddeboer\DocumentManipulationBundle\Exception\ManipulatorException;
use Ddeboer\DocumentManipulationBundle\Exception\InvalidDocumentException;
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
        
        $this->prepareTemplate($file);

        foreach ($data as $field => $value) {
            if ($value instanceof File) {
                // Image merge field
                $filename = $value->getHashFilename();
                if (!$this->liveDocx->imageExists($filename)) {
                    $tmpFile = \sys_get_temp_dir() . '/' . $filename;
                    \copy($value->getPathname(), $tmpFile);

                    try {
                        $this->liveDocx->uploadImage($tmpFile);
                    } catch (\Exception $e) {
                        throw new ManipulatorException('LiveDocx', 'merge', $e);
                    }
                }

                $value = $filename;
            } elseif (\is_array($value) && empty($value)) {
                // To prevent Undefined offset: 0 in
                // ZendService/LiveDocx/MailMerge.php line 1175
                $value = null;
            }

            $this->liveDocx->assign($field, $value);
        }

        try {
            $this->liveDocx->createDocument();
        } catch (\Exception $e) {
            throw new ManipulatorException('LiveDocx', 'merge', $e);
        }

        $contents = $this->liveDocx->retrieveDocument($format);

        return $contents;
    }
    
    /**
     * Get merge fields in the document (template)
     *
     * @return array
     */
    public function getMergeFields(File $file)
    {
        $this->prepareTemplate($file);
        
        try {
            $fields = $this->liveDocx->getFieldNames();

            $blocks = $this->liveDocx->getBlockNames();
            foreach ($blocks as $block) {
                $blocks[$block] = $this->liveDocx->getBlockFieldNames($block);
            }
        } catch (\Exception $e) {
            throw new ManipulatorException('LiveDocx', 'getMergeFields', $e);
        }

        return $fields + $blocks;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($type, $operation)
    {
        if (in_array($type, array('doc', 'docx', 'rtf'))
            && in_array($operation, array('merge', 'convert', 'getMergeFields'))) {
            return true;
        }

        return false;
    }
    
    /**
     * Upload file to LiveDocx if it hasn't yet been uploaded
     * 
     * @param File $file
     */
    protected function prepareTemplate(File $file)
    {
        $hash = $file->getHashFilename();
        
        // Upload local template to server if it hasn't been uploaded yet
        if (!$this->liveDocx->templateExists($hash)) {
            $tmpFile = sys_get_temp_dir() . '/' . $hash;
            copy($file->getPathname(), $tmpFile);

            // Make sure file doesn't become writable only by www-data
            chmod($tmpFile, 0666 & ~umask());

            // If LiveDocx throws an error at uploadTemplate, this is usually
            // because the template is invalid, e.g., it has an incorrect file 
            // extension.
            try {
                $this->liveDocx->uploadTemplate($tmpFile);
            } catch (\Exception $e) {
                throw new InvalidDocumentException($file, $e);
            }
        }
        
        $this->liveDocx->setRemoteTemplate($hash);
    }
}

