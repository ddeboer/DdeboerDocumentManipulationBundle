<?php

namespace Ddeboer\DocumentManipulationBundle;

interface DocumentInterface
{
    public function save($filename);

    public function concatenate()
}