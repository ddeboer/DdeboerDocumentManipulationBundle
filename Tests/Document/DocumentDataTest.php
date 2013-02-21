<?php
namespace Ddeboer\DocumentManipulationBundle\Tests\Document;

use Ddeboer\DocumentManipulationBundle\Document\DocumentData;
use Ddeboer\DocumentManipulationBundle\Document\Image;

class DocumentDataTest extends \PHPUnit_Framework_TestCase
{
    public function testSetScalar()
    {
        $data = new DocumentData();
        $data->set('Name', 'James');
        $data->set('Age', 66);

        $this->assertEquals('James', $data->get('Name'));
        $this->assertEquals(66, $data->get('Age'));
    }

    public function testSetValidArray()
    {
        $data = new DocumentData();
        $data->set('ValidBlock', array(
            array(
                'Name' => 'James'
            ),
            array(
                'Name' => 'Moneypenny'
            ),
            array(
                'Name' => 'Q'
            )
        ));
        $this->assertCount(3, $data->get('ValidBlock'));

        $data->set('EmptyBlock', array());
        $this->assertEquals(array(), $data->get('EmptyBlock'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetInvalidArray()
    {
        $data = new DocumentData;
        $data->set('InvalidBlock', array('test'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetInvalidValue()
    {
        $data = new DocumentData;
        $data->set('InvalidValue', new \stdClass());
    }

    public function testSetImage()
    {
        $data = new DocumentData();
        $data->set('image:Photo', Image::fromFilename(__DIR__.'/../Fixtures/photo.jpg'));
    }
}