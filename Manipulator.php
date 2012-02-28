<?php

namespace Ddeboer\DocumentManipulationBundle;

use Ddeboer\DocumentManipulationBundle\Manipulator\DocManipulator;
use Ddeboer\DocumentManipulationBundle\Manipulator\PdfManipulator;

class Manipulator implements ManipulatorInterface
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
     * Open a file and return a document
     *
     * @return Document
     */
    public function open($filename)
    {
        return new Document($filename);
    }

    public function load($string)
    {

    }

    /**
     * Concatenate two or more documents
     * 
     * @param array $documents
     * @return Document         Concatenated document
     * @throws \InvalidArgumentException
     */
    public function concatenate(array $documents)
    {
        $files = array();
        foreach ($documents as $document) {
            if (Document::TYPE_PDF != $document->getType()) {
                throw new \InvalidArgumentException(
                    'For concatenation, both documents must be PDFs'
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

    /**
     * Convert a document from one type into another
     *
     * @param Document $document
     * @param string $toType    Type to convert into
     */
    public function convert(Document $document, $toType)
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