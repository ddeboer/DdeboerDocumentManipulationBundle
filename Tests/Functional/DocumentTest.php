<?php

namespace Ddeboer\DocumentManipulationBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Ddeboer\DocumentManipulationBundle\Document\Document;
use Ddeboer\DocumentManipulationBundle\Document\DocumentData;
use Symfony\Component\HttpFoundation\File\File;

//class DocumentTest extends WebTestCase
//{
//    protected $manipulators;
//
//    protected $factory;
//
//    public function setup()
//    {
//        $client = $this->createClient();
//        $client->
//        $this->manipulators = $client->getContainer()
//            ->get('ddeboer_document_manipulation.manipulator_chain');
//
//        $this->factory = $client->getContainer()
//            ->get('ddeboer_document_manipulation.factory');
//    }

//    public function testMergeDocx()
//    {
//        $document = new Document($this->manipulators);
//        $document->setFile(new File(__DIR__.'/../Fixtures/document.docx'));
//        $data = new DocumentData(array('Name' => 'Bond', 'FirstName' => 'James'));
//        $document
//            ->merge($data)
//            ->save('/tmp/output1.pdf');
//    }
//
//    public function testMergeDoc()
//    {
//        $document = new Document($this->manipulators);
//        $document->setFile(new File(__DIR__.'/../Fixtures/document.doc'));
//        $data = new DocumentData(array('Name' => 'Bond', 'FirstName' => 'James'));
//        $document
//            ->merge($data)
//            ->save('/tmp/output2.pdf');
//    }
//
//    public function testAppend()
//    {
//        $document1 = new Document($this->manipulators);
//        $document1->setFile(new File('/tmp/output1.pdf'));
//
//        $document2 = new Document($this->manipulators);
//        $document2->setFile(new File('/tmp/output2.pdf'));
//
//        $document = $document1
//            ->append($document2)
//            ->save('/tmp/output3.pdf');
//
//        $document1 = new Document($this->manipulators);
//        $document1->setFile(new File('/tmp/output1.pdf'));
//
//        $document2 = new Document($this->manipulators);
//        $document2->setFile(new File('/tmp/output2.pdf'));
//
//        $document3 = new Document($this->manipulators);
//        $document3->setFile(new File('/tmp/output3.pdf'));
//
//        $document = $document1
//            ->append($document2)
//            ->append($document3)
//            ->save();
//    }
//
//    public function testMergeAndAppend()
//    {
//        $this->factory
//            ->open(__DIR__.'/Fixtures/document.doc')
//            ->merge(new DocumentData(array('Name' => 'Bond')))
//            ->append($this->factory->open('/tmp/output1.pdf'))
//            ->save('/tmp/merge_and_append.pdf');
//    }
//}