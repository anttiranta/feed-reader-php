<?php
namespace App\Antti\FeedsAppItemImportBundle\Logger;

use Monolog\Logger;

class Error extends \Monolog\Handler\StreamHandler 
{
    /**
     * @var int
     */
    protected $level = Logger::ERROR;
    
    /**
     * @var string
     */
    protected $fileName = 'var/log/antti_feedsapp_item_import_error.log';
    
    public function __construct($bubble = true, $filePermission = null, $useLocking = false)
    {
        parent::__construct($this->fileName, $this->level, $bubble, $filePermission, $useLocking);
    }
}