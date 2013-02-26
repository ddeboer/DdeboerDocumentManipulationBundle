<?php
namespace Ddeboer\DocumentManipulationBundle\Tests\Manipulator;

use Ddeboer\DocumentManipulationBundle\Document\DocumentData;
use Ddeboer\DocumentManipulationBundle\Manipulator\ManipulatorChain;

class ManipulatorChainTest extends \PHPUnit_Framework_TestCase
{
    public function testMerge()
    {
        $manipulator = $this->getMock(
            '\Ddeboer\DocumentManipulationBundle\Manipulator\ManipulatorInterface',
            array(
                'supports', 'merge', 'getFile'
            )
        );

        $manipulator->expects($this->any())
            ->method('supports')
            ->with('doc', 'merge')
            ->will($this->returnValue(true));
        $manipulator->expects($this->any())
            ->method('merge')
            ->with('file')
            ->will($this->returnValue(true));

        $chain = new ManipulatorChain(array($manipulator));

        $doc = $this->getMockBuilder(
            '\Ddeboer\DocumentManipulationBundle\Document\Document'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $doc->expects($this->any())
            ->method('getType')
            ->will($this->returnValue('doc'));

        $doc->expects($this->any())
            ->method('getFile')
            ->will($this->returnValue('file'));

        $data = new DocumentData();
        $chain->merge($doc, $data);
    }

    /**
     * @expectedException \Ddeboer\DocumentManipulationBundle\Exception\ManipulatorNotFoundException
     */
    public function testManipulatorNotFoundExceptionIsThrown()
    {
        $chain = new ManipulatorChain();
        $chain->findManipulator('type', 'operation');

    }
}
