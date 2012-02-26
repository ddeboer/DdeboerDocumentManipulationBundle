<?php

namespace Ddeboer\DocumentManipulationBundle\Manipulator\Doc;

use Ddeboer\DocumentManipulationBundle\DocumentFactoryInterface;
use Ddeboer\DocumentManipulationBundle\ManipulatorInterface;
use Ddeboer\DocumentManipulationBundle\DocumentData;
use Zend\Service\LiveDocx\MailMerge;

class Manipulator implements ManipulatorInterface
{
    protected $factory;
    
    protected $liveDocx;

    public function __construct(DocumentFactoryInterface $factory, $liveDocx)
    {
        $this->factory = $factory;
        $this->liveDocx = $liveDocx;
    }

    public function supports($mimeType)
    {
        return 'application/msword' == $mimeType;
    }

    public function concatenate($documents)
    {
        
    }

    public function convertTo($type)
    {
        return $this->merge(null, $type);
    }

    public function merge(DocumentData $data = null, $type = 'pdf')
    {
        $string = $this->liveDocx->retrieveDocument();
        return $this->factory->load($string);        
    }
}