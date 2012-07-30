<?php

namespace Ddeboer\DocumentManipulationBundle\Document;

/**
 * A document
 */
interface DocumentInterface
{
    const TYPE_DOC  = 'doc';
    const TYPE_DOCX = 'docx';
    const TYPE_PDF  = 'pdf';

    /**
     * Save the document to a file
     *
     * @param string $filename Filename to save to
     *
     * @return DocumentInterface
     */
    function save($filename = null);

    /**
     * (Mail) merge this document with data
     *
     * @param DocumentDataInterface $data Data to merge into document
     *
     * @return DocumentInterface
     */
    function merge(DocumentData $data);

    /**
     * Append another document to this document
     *
     * @param DocumentInterface $document Document to append
     *
     * @return DocumentInterface
     */
    function append(DocumentInterface $document);

    /**
     * Append multiple documents to this document
     *
     * @param DocumentInterface[] $documents Documents to append
     *
     * @return DocumentInterface
     */
    function appendMultiple(array $documents);

    /**
     * Append this document to another document
     *
     * @param DocumentInterface $document Document to append this document to
     *
     * @return DocumentInterface
     */
    function appendTo(self $document);

    /**
     * Prepend another document to this document
     *
     * @param DocumentInterface $document Document to prepend to this document
     *
     * @return DocumentInterface
     */
    function prepend(self $document);

    /**
     * Prepend multiple documents to this document
     *
     * @param DocumentInterface[] $documents Documents to prepend to this
     *                                       document
     */
    function prependMultiple(array $documents);

    /**
     * Prepend this document to another document
     *
     * @param DocumentInterface $document Document to prepend this document to
     *
     * @return DocumentInterface
     */
    function prependTo(self $document);

    /**
     * Put this document in front of another document
     *
     * @param DocumentInterface $background The background document
     *
     * @return DocumentInterface $background
     */
    function putInFront(self $background);

    /**
     * Put this document behind another document
     *
     * @param DocumentInterface $foreground
     *
     * @return DocumentInterface
     */
    function putBehind(self $foreground);
}