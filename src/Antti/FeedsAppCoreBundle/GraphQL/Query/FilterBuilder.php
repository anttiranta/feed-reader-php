<?php
namespace App\Antti\FeedsAppCoreBundle\GraphQL\Query;

use App\Antti\FeedsAppCoreBundle\GraphQL\Query\Filter;

class FilterBuilder 
{
    /**
     * @var array
     */
    private $conditionMap = [
        'eq' => '=',
    ];
    
    public function build(string $field, array $data): Filter
    {
        $condition = array_keys($data[$field])[0];
        $value = $data[$field][$condition];
        
        if ($condition === 'like') {
            $value = $value.'%';
        }
        
        $condition = $this->conditionMap[$condition] ?? $condition;

        return new Filter($field, $condition, $value);
    }
}
