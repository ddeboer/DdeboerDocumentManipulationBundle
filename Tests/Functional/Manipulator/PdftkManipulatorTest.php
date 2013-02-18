<?php

namespace Ddeboer\DocumentManipulationBundle\Tests\Functional\Manipulator;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @group functional
 */
class PdftkManipulatorTest extends WebTestCase
{
    protected $client;

    public function setUp()
    {
        $this->client = $this->createClient();
    }

    public function testGetManipulatorServiceFromContainer()
    {
        $manipulator = $this->client->getContainer()->get('ddeboer_document_manipulation.manipulator.pdftk_manipulator');
        $this->assertInstanceOf('\Ddeboer\DocumentManipulationBundle\Manipulator\PdftkManipulator', $manipulator);
    }
}