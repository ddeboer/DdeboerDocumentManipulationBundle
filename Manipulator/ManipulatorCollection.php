<?php

namespace Ddeboer\DocumentManipulationBundle\Manipulator;

use Ddeboer\DocumentManipulationBundle\ManipulatorInterface;

class ManipulatorCollection
{
    protected $manipulators = array();

    public function __construct(array $manipulators = array())
    {
        foreach ($manipulators as $manipulator) {
            $this->add($manipulator);
        }
    }

    public function add(ManipulatorInterface $manipulator)
    {
        $this->manipulators[] = $manipulator;
    }

    public function findManipulator($type, $operation)
    {
        $filtered = array_filter($this->manipulators, function($manipulator) use ($type, $operation) {
            return $manipulator->supports($type)
                && method_exists($manipulator, $operation);
        });

        if (0 === count($filtered)) {
            throw new \Exception('No manipulator found for type ' . $type
                . ' and operation ' . $operation);
        }

        return $filtered[0];
    }
}