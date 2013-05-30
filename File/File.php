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
        $extension = self::guessExtensionFromContents($string);
        
        $filename = sys_get_temp_dir() . '/' . md5($string) . ($extension ? '.' . $extension : '');
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
    
    /**
     * Guess extension based on file contents
     * 
     * @param string $file File contents
     *
     * @return string Guessed file extension
     */
    public static function guessExtensionFromContents($file)
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        switch ($finfo->buffer($file)) {
            case 'application/pdf':
                return 'pdf';
            
            case 'application/msword':
            case 'application/zip':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                $finfo2 = new \finfo();
                $info = $finfo2->buffer($file);
                switch ($info) {
                    case 'Microsoft Office Document Microsoft Word Document':
                        return 'doc';

                    default:
                    case 'Microsoft Word 2007+':
                        return 'docx';
                }
                
            case 'text/rtf':
            case 'application/rtf':
                return 'rtf';
        }
   }
}