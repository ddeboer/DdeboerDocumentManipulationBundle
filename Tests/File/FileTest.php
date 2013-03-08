<?php
namespace Ddeboer\DocumentManipulationBundle\Tests\File;

use Ddeboer\DocumentManipulationBundle\File\File;

class FileTest extends \PHPUnit_Framework_TestCase
{
    public function testGetExtension()
    {
        $file = File::fromString(\file_get_contents(__DIR__.'/../Fixtures/document.doc'));
        $this->assertEquals('doc', $file->getExtension());
        
        $file = File::fromString(\file_get_contents(__DIR__.'/../Fixtures/document.docx'));
        $this->assertEquals('docx', $file->getExtension());
        
        $file = File::fromString(\file_get_contents(__DIR__.'/../Fixtures/document.rtf'));
        $this->assertEquals('rtf', $file->getExtension());

        $file = File::fromString(\file_get_contents(__DIR__.'/../Fixtures/letterhead.pdf'));
        $this->assertEquals('pdf', $file->getExtension());
    }
}