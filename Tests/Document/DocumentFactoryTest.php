<?php

namespace Ddeboer\DocumentManipulationBundle\Tests\Document;

use Ddeboer\DocumentManipulationBundle\Document\DocumentFactory;
use Ddeboer\DocumentManipulationBundle\Document\DocumentInterface;
use Ddeboer\DocumentManipulationBundle\Manipulator\ManipulatorChain;

class DocumentFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testOpen()
    {
        $factory = new DocumentFactory(new ManipulatorChain(array()));
        $document = $factory->open(__DIR__.'/../Fixtures/document.docx');
        $this->assertInstanceOf('Ddeboer\DocumentManipulationBundle\Document\DocumentInterface', $document);
    }

    public function testLoad()
    {
        $factory = new DocumentFactory(new ManipulatorChain(array()));
        $string = file_get_contents(__DIR__.'/../Fixtures/document.doc');
        $document = $factory->load($string, DocumentInterface::TYPE_PDF);
    }
}