<?php

namespace Ddeboer\DocumentManipulationBundle\Manipulator;

use Ddeboer\DocumentManipulationBundle\Document\DocumentInterface;
use Ddeboer\DocumentManipulationBundle\File\File;
use Ddeboer\DocumentManipulationBundle\Manipulator\PdftkManipulator\Pdftk;

/**
 * Manipulates PDF documents with pdftk
 *
 */
class PdftkManipulator implements ManipulatorInterface
{
    protected $pdftk;

    protected $supportedOperations = array(
        'append',
        'appendMultiple',
        'prepend',
        'layer'
    );

    /**
     * Constructor
     *
     * @param Pdftk $pdftk
     */
    public function __construct(Pdftk $pdftk)
    {
        $this->pdftk = $pdftk;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($type, $operation)
    {
        return in_array($type, array('pdf'))
            && in_array($operation, $this->supportedOperations);
    }

    /**
     * Append one file
     *
     * @param File $file1
     * @param File $file2
     *
     * @return File
     */
    public function append(File $file1, File $file2)
    {
        $files = array(
            $file1->getPathname(),
            $file2->getPathname()
        );

        $outputFile = $this->pdftk->merge($files);

        // fromString to get proper (MD5) filename
        return File::fromString(\file_get_contents($outputFile));
    }

    /**
     * Append multiple files
     *
     * @param File  $file
     * @param array $files
     *
     * @return File
     */
    public function appendMultiple(File $file, array $files)
    {
        $filenames = array(
            $file->getPathname()
        );

        foreach ($files as $file) {
            $filenames[] = $file->getPathname();
        }

        $outputFile = $this->pdftk->merge($filenames);

        // fromString to get proper (MD5) filename
        return File::fromString(\file_get_contents($outputFile));
    }

    /**
     * Layer foreground before background
     *
     * @param File $foreground
     * @param File $background
     *
     * @return File
     */
    public function layer(File $foreground, File $background)
    {
        $file = $this->pdftk->background(
            $foreground->getPathname(),
            $background->getPathname()
        );

        // fromString to get proper (MD5) filename
        return File::fromString(\file_get_contents($file));
    }
}