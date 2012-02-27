<?php

namespace Ddeboer\DocumentManipulationBundle;

/**
 * A document
 */
interface DocumentInterface
{
    /**
     * Save the document to a file
     *
     * @param string $filename  Filename to save to
     * @param string $type      Type to save file as 
     * @return DocumentInterface
     */
    function save($filename, $type = null);

    /**
     * (Mail) merge this document with data
     *
     * @param DocumentDataInterface $data   Data to merge into document
     * @return DocumentInterface
     */
    function merge(DocumentDataInterface $data);

    /**
     * Append another document to this document
     * 
     * @return DocumentInterface 
     */
    function append(self $document);

    /**
     * Append multiple documents to this document
     *
     * @return DocumentInterface
     */
    function appendMultiple(array $documents);

    /**
     * Append this document to another document
     *
     * @return DocumentInterface
     */
    function appendTo(self $document);

    /**
     * Prepend another document to this document
     *
     * @return DocumentInterface
     */
    function prepend(self $document);

    /**
     * Prepend multiple documents to this document
     */
    function prependMultiple(array $documents);

    /**
     * Prepend this document to another document
     *
     * @return DocumentInterface
     */
    function prependTo(self $document);

    /**
     * Put this document in front of another document
     *
     * @return DocumentInterface $background
     * @param DocumentInterface $document   The background document
     */
    function putInFront(self $background);

    /**
     * Put this document behind another document
     *
     * @param DocumentInterface $foreground
     * @return DocumentInterface
     */
    function putBehind(self $foreground);
}