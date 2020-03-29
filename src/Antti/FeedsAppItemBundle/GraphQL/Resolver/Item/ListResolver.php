<?php
namespace App\Antti\FeedsAppItemBundle\GraphQL\Resolver\Item;

use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use App\Antti\FeedsAppItemBundle\Repository\ItemRepository;
use App\Antti\FeedsAppItemBundle\GraphQL\Resolver\Item\QueryBuilderProcessor\PaginationProcessor;
use App\Antti\FeedsAppItemBundle\GraphQL\Resolver\Item\QueryBuilderProcessor\FilterProcessor;
use App\Antti\FeedsAppItemBundle\GraphQL\Resolver\Item\QueryBuilderProcessor\ProcessorFactory;

class ListResolver implements ResolverInterface 
{
    /**
     * @var array 
     */
    private $allowedFilters = [
        'title',
        'categoryName'
    ];
    
    /**
     * @var array 
     */
    private $fieldMapping = [
        'title' => 'item.title',
        'categoryName' => 'cat.name'
    ];
    
    /**
     * @var ItemRepository
     */
    private $itemRepository;
    
    /**
     * @var ProcessorFactory
     */
    private $processorFactory;

    public function __construct(
        ItemRepository $itemRepository,
        ProcessorFactory $processorFactory
    ) {
        $this->itemRepository = $itemRepository;
        $this->processorFactory = $processorFactory;
    }
    
    public function resolve(ArgumentInterface $args): array 
    {
        $result = [];
        
        $qb = $this->itemRepository->createQueryBuilder('item');
        
        $queryBuilderProcessor = $this->processorFactory->create([
            new PaginationProcessor(),
            new FilterProcessor($this->allowedFilters, $this->fieldMapping)
        ]);
        $queryBuilderProcessor->process($args, $qb);
        
        $result['items'] = $qb->getQuery()->getResult();
        
        // TODO: there must be way to perform this only when needed...
        $result['totalCount'] = $this->getTotalCount($args);

        return $result;
    }
    
    protected function getTotalCount(ArgumentInterface $args): int
    {
        $qb = $this->itemRepository->createQueryBuilder('item');
        $qb->select($qb->expr()->count('item.id'));
        
        $queryBuilderProcessor = $this->processorFactory->create([
            new FilterProcessor($this->allowedFilters, $this->fieldMapping)
        ]);
        $queryBuilderProcessor->process($args, $qb);
        
        return (int)$qb->getQuery()->getSingleScalarResult();
    }
}
