<?php

namespace Ddeboer\DocumentManipulationBundle;

interface ManipulatorInterface
{
    /**
     * @return boolean
     */
    function supports($type, $operation);
}