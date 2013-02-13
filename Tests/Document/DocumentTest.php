<?php

namespace Ddeboer\DocumentManipulationBundle\Tests\Document;

use Ddeboer\DocumentManipulationBundle\Document\DocumentFactory;
use Ddeboer\DocumentManipulationBundle\Document\DocumentInterface;
use Ddeboer\DocumentManipulationBundle\Manipulator\ManipulatorChain;

class DocumentTest extends \PHPUnit_Framework_TestCase
{
    public function testGetType()
    {
        $factory = new DocumentFactory(new ManipulatorChain(array()));

        $document = $factory->open(__DIR__.'/../Fixtures/document.docx');
        $this->assertEquals('docx', $document->getType());

        $document = $factory->load(\file_get_contents(__DIR__.'/../Fixtures/document.docx'));
        $this->assertEquals('docx', $document->getType());

        $document = $factory->open(__DIR__.'/../Fixtures/document.doc');
        $this->assertEquals('doc', $document->getType());

        $document = $factory->load(\file_get_contents(__DIR__.'/../Fixtures/document.doc'));
        $this->assertEquals('doc', $document->getType());

        $document = $factory->open(__DIR__.'/../Fixtures/output.pdf');
        $this->assertEquals('pdf', $document->getType());

        $document = $factory->load(file_get_contents(__DIR__.'/../Fixtures/output.pdf'));
        $this->assertEquals('pdf', $document->getType());
    }
}