<?php
namespace App\Antti\FeedsAppItemImportBundle\Model\Item\Import\Job;

use Psr\Log\LoggerInterface;

abstract class AbstractJob 
{
    /**
     * @var LoggerInterface 
     */
    private $logger;
    
    public function __construct(
       LoggerInterface $logger = null) 
    {
        $this->logger = $logger !== null ? $logger : new \Psr\Log\NullLogger();
    }
    
    public function setLogger(LoggerInterface $logger) 
    {
        $this->logger = $logger;
    }

    public function getLogger(): ?LoggerInterface
    {
        return $this->logger;
    }
}