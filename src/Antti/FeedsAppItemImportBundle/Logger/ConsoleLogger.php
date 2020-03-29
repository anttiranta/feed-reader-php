<?php
namespace App\Antti\FeedsAppItemImportBundle\Logger;

use Psr\Log\LogLevel;

class ConsoleLogger 
    extends \Symfony\Component\Console\Logger\ConsoleLogger 
    implements \Psr\Log\LoggerInterface
{
   /**
     * @inheritdoc
     */
    public function emergency($message, array $context = array())
    {
        parent::log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * @inheritdoc
     */
    public function alert($message, array $context = array())
    {
        parent::log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * @inheritdoc
     */
    public function critical($message, array $context = array())
    {
        parent::log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * @inheritdoc
     */
    public function error($message, array $context = array())
    {
        parent::log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * @inheritdoc
     */
    public function warning($message, array $context = array())
    {
        parent::log(LogLevel::WARNING, $message, $context);
    }

    /**
     * @inheritdoc
     */
    public function notice($message, array $context = array())
    {
        parent::log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * @inheritdoc
     */
    public function info($message, array $context = array())
    {
        parent::log(LogLevel::INFO, $message, $context);
    }

    /**
     * @inheritdoc
     */
    public function debug($message, array $context = array())
    {
        parent::log(LogLevel::DEBUG, $message, $context);
    }
}
