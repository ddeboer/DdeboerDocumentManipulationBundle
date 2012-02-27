<?php

namespace Ddeboer\DocumentManipulationBundle\Tests;

use Ddeboer\DocumentManipulationBundle\DocumentFactory;
use Ddeboer\DocumentManipulationBundle\DocumentData;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DocumentFactoryTest extends WebTestCase
{
    public function testOpen()
    {
        $client = $this->createClient();
        $manipulators = $client->getContainer()->get('ddeboer_document_manipulation.manipulator_collection');
        $factory = new DocumentFactory($manipulators);
        $document = $factory->open(__DIR__.'/Fixtures/document.docx');
        $this->assertInstanceOf('Ddeboer\DocumentManipulationBundle\DocumentInterface', $document);

        $data = new DocumentData(array('merge_field' => 'Bond, James Bond'));

        $factory
            ->open(__DIR__.'/Fixtures/document.docx')
            ->merge($data)
            ->save();
    }
}