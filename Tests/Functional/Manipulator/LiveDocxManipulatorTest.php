<?php

namespace Ddeboer\DocumentManipulationBundle\Tests\Functional\Manipulator;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LiveDocxManipulatorTest extends WebTestCase
{
    protected $client;

    public function setUp()
    {
        $this->client = $this->createClient();
    }

    public function testGetManipulatorServiceFromContainer()
    {
        $manipulator = $this->client->getContainer()->get('ddeboer_document_manipulation.manipulator.live_docx');
        $this->assertInstanceOf('\Ddeboer\DocumentManipulationBundle\Manipulator\LiveDocxManipulator', $manipulator);
    }

    public function testGetLiveDocxServiceFromContainer()
    {
        $liveDocx = $this->client->getContainer()->get('ddeboer_document.manipulation.manipulator.live_docx.mail_merge');
        $this->assertInstanceOf('\ZendService\LiveDocx\MailMerge', $liveDocx);
    }
}