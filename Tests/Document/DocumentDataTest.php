<?php
namespace Ddeboer\DocumentManipulationBundle\Tests\Document;

use Ddeboer\DocumentManipulationBundle\Document\DocumentData;
use Ddeboer\DocumentManipulationBundle\Document\Image;

class DocumentDataTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider getValidValues()
     */
    public function testSetValidValues($key, $value)
    {
        $data = new DocumentData();
        $data->set($key, $value);

        $this->assertEquals($value, $data->get($key));
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
     * @dataProvider getInvalidValues
     * @expectedException \InvalidArgumentException
     */
    public function testSetInvalidValues($key, $value)
    {
        $data = new DocumentData;
        $data->set($key, $value);
    }

    public function getValidValues()
    {
        return array(
            array('Name', 'James'),
            array('Age', 66),
            array('LicenseToKill', false),
            array('EmptyStringField', ''),
            array('NullField', null),
            array('image:Photo', Image::fromFilename(__DIR__.'/../Fixtures/photo.jpg'))
        );
    }

    public function getInvalidValues()
    {
        return array(
            array('InvalidBlock', array('test')),
            array('InvalidField', new \stdClass)
        );
    }
}