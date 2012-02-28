<?php

namespace Ddeboer\DocumentManipulationBundle\Manipulator;

use Ddeboer\DocumentManipulationBundle\ManipulatorInterface;
use Ddeboer\DocumentManipulationBundle\Document;
use Ddeboer\DocumentManipulationBundle\DocumentInterface;
use Symfony\Component\HttpFoundation\File\File;
use Ddeboer\DocumentManipulationBundle\Manipulator\PdftkManipulator\Pdftk;

class PdftkManipulator implements ManipulatorInterface
{
    protected $pdftk;

    public function __construct(Pdftk $pdftk)
    {
        $this->pdftk = $pdftk;
    }

    public function supports($type, $operation)
    {
        return in_array($type, array('pdf'))
            && in_array($operation, array('append', 'prepend'));
    }

    public function append(DocumentInterface $document1, DocumentInterface $document2)
    {
        $files = array(
            $document1->getFile()->getPathname(),
            $document2->getFile()->getPathname()
        );

        $outputFile = $this->pdftk->merge($files);
        return new File($outputFile);
        

//        $cmd = "pdftk - output -";
//        $descriptorspec = array(
//            0 => array('pipe', 'r'),
////            1 => array('pipe', 'r'),
//            1 => array("pipe", 'w')
//        );
//
//        $process = proc_open($cmd, $descriptorspec, $pipes);
//
//        if (is_resource($process)) {
//            fwrite($pipes[0], $document1->getContents());
//            fwrite($pipes[0], $document2->getContents());
////            fwrite($pipes[1], $document2->getContents());
//            fclose($pipes[0]);
////            fclose($pipes[1]);
//            $content = stream_get_contents($pipes[1]);
//            fclose($pipes[1]);
//        }
//        file_put_contents('/tmp/output3.pdf', $content);
//
//
//        echo $content;die;
//
//        return $content;
    }
}