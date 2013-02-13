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
        return new self($filename);
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
        $filename = sys_get_temp_dir() . '/' . md5($string);
        file_put_contents($filename, $string);

        return new self($filename);
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