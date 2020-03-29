<?php
namespace App\Antti\FeedsAppItemBundle\GraphQL\Resolver\Item\QueryBuilderProcessor;

use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Join;
use App\Antti\FeedsAppItemBundle\GraphQL\Resolver\Item\QueryBuilderProcessorInterface;
use App\Antti\FeedsAppCoreBundle\GraphQL\Query\Filter;
use App\Antti\FeedsAppCoreBundle\GraphQL\Query\FilterBuilder;
use App\Antti\FeedsAppItemBundle\Entity\Category;

class FilterProcessor implements QueryBuilderProcessorInterface 
{
    const FILTER = 'filter';

    private $fieldMapping;
    
    private $allowedFilters;
    
    public function __construct(
        array $allowedFilters = [],
        array $fieldMapping = []
    ) {
        $this->allowedFilters = $allowedFilters;
        $this->fieldMapping = $fieldMapping;
    }

    public function process(ArgumentInterface $args, QueryBuilder &$queryBuilder): void 
    {
        /** @var Filter $filter */
        $filter = $this->getFilter($args);
        if ($filter === null) {
            return;
        }
        
        if ($filter->getField() === 'categoryName') {
            $queryBuilder->join(Category::class, "cat", Join::WITH, 'cat.id=item.category');
        }
        
        $field = $this->getFieldMapping($filter->getField());

        $format = '%s %s :%s';
        if ($filter->getCondition() === 'in') {
            $format = '%s %s(:%s)';
        }
        
        $queryBuilder->where(
            sprintf($format, $field, $filter->getCondition(), $filter->getField())   
        );
        $queryBuilder->setParameter($filter->getField(), $filter->getValue());
    }

    private function getFilter(ArgumentInterface $args): ?Filter
    {
        $filter = $args->offsetGet(self::FILTER);

        if ($filter !== null) {
            foreach ($this->allowedFilters as $key) {
                if (isset($filter[$key])) {
                    return $this->getFilterInstance($key, $filter);
                }
            }
        }
        return null;
    }

    private function getFieldMapping(string $field): string 
    {
        return $this->fieldMapping[$field] ?? $field;
    }
    
    /**
     * @param string $field
     * @param array $data
     * @return Filter
     * @todo: refactor!
     */
    private function getFilterInstance(string $field, array $data): Filter
    {
        return (new FilterBuilder())->build($field, $data);
    }
}
