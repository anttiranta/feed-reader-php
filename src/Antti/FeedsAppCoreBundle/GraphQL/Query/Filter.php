<?php
namespace App\Antti\FeedsAppCoreBundle\GraphQL\Query;

class Filter 
{
    /**
     * @var string 
     */
    private $field;
    
    /**
     * @var string 
     */
    private $condition;
    
    /**
     * @var mixed 
     */
    private $value;
    
    public function __construct(
        string $field,
        string $condition,
        $value
    ) {
        $this->field = $field;
        $this->condition = $condition;
        $this->value = $value;
    }
    
    public function getField(): string
    {
        return $this->field;
    }
    
    public function getCondition(): string
    {
        return $this->condition;
    }
    
    public function getValue()
    {
        return $this->value;
    }
}
