<?php

namespace Ddeboer\DocumentManipulationBundle\Manipulator;

use Ddeboer\DocumentManipulationBundle\Manipulator\Pdf\Pdftk;

class PdfManipulator
{
    /**
     * @var Pdftk
     */
    private $pdftk;

    public function __construct(Pdftk $pdftk)
    {
        $this->pdftk = $pdftk;
    }

    public function concatenate(array $files, $output)
    {
        $filenames = array();
        foreach ($files as $file) {
            $filenames[] = $file->getPathname();
        }

        return $this->pdftk->merge($filenames, $output);
    }

    public function overlay($front, $back, $output)
    {
        return $this->pdftk->background($front, $back, $output);
    }
}