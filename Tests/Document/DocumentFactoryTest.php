<?php

namespace Ddeboer\DocumentManipulationBundle\Tests\Document;

use Ddeboer\DocumentManipulationBundle\Document\DocumentFactory;
use Ddeboer\DocumentManipulationBundle\Manipulator\ManipulatorChain;

class DocumentFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testOpen()
    {
        $factory = new DocumentFactory(new ManipulatorChain(array()));
        $document = $factory->open(__DIR__.'/../Fixtures/document.docx');
        $this->assertInstanceOf('Ddeboer\DocumentManipulationBundle\Document\DocumentInterface', $document);
        $this->assertEquals('application/msword', $document->getFile()->getMimeType());
    }

    public function testLoad()
    {
        $factory = new DocumentFactory(new ManipulatorChain(array()));
        $string = file_get_contents(__DIR__.'/../Fixtures/output.pdf');
        $document = $factory->load($string);

        $this->assertEquals('application/pdf', $document->getFile()->getMimeType());
    }
}