<?php
namespace Ddeboer\DocumentManipulationBundle\File;

use Symfony\Component\HttpFoundation\File\File as SymfonyFile;

/**
 * Generic file object
 */
class File extends SymfonyFile
{
    /**
     * @var File
     */
    protected $file;

    protected $contents;

    /**
     * Create file object from filename
     *
     * @param string $filename
     *
     * @return \self
     */
    public static function fromFilename($filename)
    {
        return new static($filename);
    }

    /**
     * Create file object from file contents
     *
     * @param string $string
     *
     * @return \self
     */
    public static function fromString($string)
    {
        // Try to determine file extension
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $extension = null;
        switch ($finfo->buffer($string)) {
            case 'application/pdf':
                $extension = '.pdf';
                break;

            case 'application/msword':
            case 'application/zip':
                $finfo2 = new \finfo();
                $info = $finfo2->buffer($string);
                switch ($info) {
                    case 'Microsoft Office Document Microsoft Word Document':
                        $extension = '.doc';
                        break;

                    case 'Microsoft Word 2007+':
                    default:
                        $extension = '.docx';
                        break;
                }
        }

        $filename = sys_get_temp_dir() . '/' . md5($string) . $extension;
        file_put_contents($filename, $string);

        return new static($filename);
    }

    /**
     * Get file contents as string
     *
     * @return string
     */
    public function getContents()
    {
        if (null === $this->contents) {
            $this->contents = \file_get_contents($this->getPathname());
        }

        return $this->contents;
    }


    /**
     * Get MD5 hash based on this file's contents
     *
     * @return type
     */
    public function getHash()
    {
        return md5($this->getContents());
    }

    /**
     * Get filename based on contents hash with file extension
     */
    public function getHashFilename()
    {
        return $this->getHash() . '.' . $this->getExtension();
    }
}