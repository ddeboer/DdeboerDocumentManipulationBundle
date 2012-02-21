<?php

namespace Ddeboer\DocumentManipulationBundle;

use Ddeboer\DocumentManipulationBundle\Manipulator\DocManipulator;
use Ddeboer\DocumentManipulationBundle\Manipulator\PdfManipulator;

class Manipulator
{
    /**
     * @var PdfManipulator
     */
    private $pdfManipulator;

    /**
     * @var DocManipulator
     */
    private $docManipulator;

    public function __construct(DocManipulator $docManipulator,
        PdfManipulator $pdfManipulator)
    {
        $this->docManipulator = $docManipulator;
        $this->pdfManipulator = $pdfManipulator;
    }

    /**
     * Concatenate two or more documents
     * 
     * @param array $documents
     * @return Document
     * @throws \InvalidArgumentException
     */
    public function concatenate(array $documents)
    {
        $files = array();
        foreach ($documents as $document) {
            if (Document::TYPE_PDF != $document->getType()) {
                throw new \InvalidArgumentException(
                    'For concatenation, both document must be PDFs'
                );
            }

            $files[] = $document->getFile();
        }

        $outputFile = $this->pdfManipulator->concatenate(
            $files, $this->createTempFile('pdf')
        );

        return new Document($outputFile);
    }

    /**
     * Overlay one file on another
     * 
     * @param Document $foreground
     * @param Document $background
     * @return Document
     */
    public function overlay(Document $foreground, Document $background)
    {
        if (Document::TYPE_PDF != $foreground->getType() 
            || Document::TYPE_PDF != $background->getType()) {
            throw new \InvalidArgumentException('Only possible with PDF');
        }

        $outputFile = $this->pdfManipulator->overlay(
            $foreground->getFile()->getPathname(),
            $background->getFile()->getPathname(),
            $this->createTempFile('pdf')
        );

        return new Document($outputFile, 'pdf');
    }

    public function convert($file, $type)
    {
        
    }

    /**
     * (Mail) merge a document with data
     *
     * @param Document $document
     * @param DocumentData $data
     * @return Document     Merged document
     * @throws \InvalidArgumentException
     */
    public function merge(Document $document, DocumentData $data, $format = 'pdf')
    {
        if (Document::TYPE_DOC != $document->getType()) {
            throw new \InvalidArgumentException(
                '(Mail) merging for now is only possible with Doc files'
            );
        }

        $outputFile = $this->docManipulator->merge(
            $document->getFile(), $data, $this->createTempFile()
        );

        return new Document($outputFile, $format);
    }

    private function createTempFile($format = null)
    {
        $file = tempnam('/tmp', 'doc_');
        if ($format) {
            $file .= '.' . $format;
        }

        return $file;
    }
}