<?php
namespace App\Antti\FeedsAppItemBundle\GraphQL\Resolver\Item\QueryBuilderProcessor;

use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use Doctrine\ORM\QueryBuilder;
use \App\Antti\FeedsAppItemBundle\GraphQL\Resolver\Item\QueryBuilderProcessorInterface;

class PaginationProcessor implements QueryBuilderProcessorInterface
{
    const MAX_ENTRIES_RETURN_AMOUNT = 100;
    const DEFAULT_ENTRIES_RETURN_AMOUNT = 20;
    const CURRENT_PAGE = 'p';
    const PAGE_SIZE = 'limit';
    
    public function process(ArgumentInterface $args, QueryBuilder &$queryBuilder): void
    {
        $queryBuilder->setMaxResults($this->getPageSize($args));
        
        $curPage = $this->getCurPage($args);
        if ($curPage !== null) {
            $queryBuilder->setFirstResult($curPage);
        }
    }
    
    private function getPageSize(ArgumentInterface $args): int
    {
        $pageSize = (int)$args->offsetGet(self::PAGE_SIZE);
        
        if ($pageSize > self::MAX_ENTRIES_RETURN_AMOUNT) {
            $pageSize = self::MAX_ENTRIES_RETURN_AMOUNT;
        }
        if ($pageSize < 1) {
            $pageSize = self::DEFAULT_ENTRIES_RETURN_AMOUNT;
        }
        return $pageSize;
    }
    
    private function getCurPage(ArgumentInterface $args): ?int
    {
        $result = null;
        $currentPage = (int)$args->offsetGet(self::CURRENT_PAGE);

        if ($currentPage > 0) {
            $result = ($currentPage - 1) * $this->getPageSize($args);
        }
        return $result;
    }
}
