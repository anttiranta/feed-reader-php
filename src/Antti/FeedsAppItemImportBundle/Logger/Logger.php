<?php
namespace App\Antti\FeedsAppItemImportBundle\Logger;

use \Monolog\Logger as BaseLogger;
use App\Antti\FeedsAppItemImportBundle\Logger\Error as ErrorHandler;
use App\Antti\FeedsAppItemImportBundle\Logger\Info as InfoHandler;

class Logger extends BaseLogger
{
    /**
     * @param string $name
     * @param HandlerInterface[] $handlers
     * @param callable $processors
     */
    public function __construct(
        $name = 'antti_fa_item_import',
        $handlers = array(), 
        callable $processors = null
    ) {
        if (empty($handlers)) {
            $handlers = [new ErrorHandler(), new InfoHandler()];
        }
        if ($processors === null){
            $processors = array();
        }
        parent::__construct($name, $handlers, $processors);
    }
}