<?php

namespace Ddeboer\DocumentManipulationBundle\Tests\Manipulator;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\File;
use Ddeboer\DocumentManipulationBundle\DocumentData;

class DocManipulatorTest extends WebTestCase
{
    public function testMerge()
    {
        $client = $this->createClient();
        $manipulator = $client->getContainer()
            ->get('ddeboer_document_manipulation.doc_manipulator');

        $file = new File(__DIR__ . '/../Fixtures/template.doc');
        $data = new DocumentData(array(
            'name'  => 'Test'
        ));

        $tmp = tempnam('tmp', 'test_');
        $result = $manipulator->merge($file, $data, $tmp);
        var_dump($result);die;
    }
}