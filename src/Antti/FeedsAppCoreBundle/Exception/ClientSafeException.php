<?php
namespace App\Antti\FeedsAppCoreBundle\Exception;

use GraphQL\Error\ClientAware;

class ClientSafeException extends \Exception implements ClientAware
{
    const DEFAULT_CATEGORY = 'businessLogic';
    
    private $category;
    
    public function isClientSafe()
    {
        return true;
    }
    
    public function getCategory()
    {
        return !empty($this->category) ? $this->category : self::DEFAULT_CATEGORY;
    }
    
    public function setCategory($category)
    {
        $this->category = $category;
    }
}