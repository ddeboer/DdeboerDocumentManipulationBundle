<?php

namespace Ddeboer\DocumentManipulationBundle\Manipulator;

use Ddeboer\DocumentManipulationBundle\Document\DocumentInterface;
use Symfony\Component\HttpFoundation\File\File;
use Ddeboer\DocumentManipulationBundle\Manipulator\PdftkManipulator\Pdftk;

/**
 * Manipulates PDF documents with pdftk
 *
 */
class PdftkManipulator implements ManipulatorInterface
{
    protected $pdftk;

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
            && in_array($operation, array(
                'append', 'appendMultiple',
                'prepend'));
    }

    public function append(DocumentInterface $document1, DocumentInterface $document2)
    {
        $files = array(
            $document1->getFile()->getPathname(),
            $document2->getFile()->getPathname()
        );

        $outputFile = $this->pdftk->merge($files);

        return new File($outputFile);
    }

    public function appendMultiple(DocumentInterface $document, array $documents)
    {
        $files = array(
            $document->getFile()->getPathname()
        );

        foreach ($documents as $document) {
            $files[] = $document->getFile()->getPathname();
        }

        $outputFile = $this->pdftk->merge($files);
        return new File($outputFile);
    }

    public function layer(DocumentInterface $foreground, DocumentInterface $background)
    {
        $filename = $this->pdftk->background(
            $foreground->getFile()->getPathname(),
            $background->getFile()->getPathname()
        );

        return file_get_contents($filename);
    }
}