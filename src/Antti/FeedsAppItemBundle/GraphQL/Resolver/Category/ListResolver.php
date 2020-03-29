<?php
namespace App\Antti\FeedsAppItemBundle\GraphQL\Resolver\Category;

use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use App\Antti\FeedsAppItemBundle\Repository\CategoryRepository;

class ListResolver implements ResolverInterface {

    const MAX_ENTRIES_RETURN_AMOUNT = 100;
    const DEFAULT_ENTRIES_RETURN_AMOUNT = 20;
    
    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository) 
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function resolve(Argument $args) 
    {
        $rawArgs = $args->getArrayCopy();
        
        $pageSize = $this->getPageSize($rawArgs);
        
        $allCategories = $this->categoryRepository->findBy([], null, $pageSize);
        return ['categories' => $allCategories];
    }

    private function getPageSize(array $rawArgs): int
    {
        $pageSize = (int)($rawArgs['limit'] ?? 0);
        
        if ($pageSize > self::MAX_ENTRIES_RETURN_AMOUNT) {
            $pageSize = self::MAX_ENTRIES_RETURN_AMOUNT;
        }
        if ($pageSize < 1) {
            $pageSize = self::DEFAULT_ENTRIES_RETURN_AMOUNT;
        }
        return $pageSize;
    }
}
