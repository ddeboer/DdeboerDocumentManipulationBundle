<?php

namespace Ddeboer\DocumentManipulationBundle\Tests;

use Ddeboer\DocumentManipulationBundle\DocumentFactory;
use Ddeboer\DocumentManipulationBundle\DocumentData;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Ddeboer\DocumentManipulationBundle\Manipulator\ManipulatorCollection;

use Ddeboer\DocumentManipulationBundle\Document;

class DocumentFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testOpen()
    {
        $factory = new DocumentFactory(new ManipulatorCollection(array()));
        $document = $factory->open(__DIR__.'/Fixtures/document.docx');
        $this->assertInstanceOf('Ddeboer\DocumentManipulationBundle\DocumentInterface', $document);
    }

    public function testLoad()
    {

    }
}