<?php
namespace App\Antti\FeedsAppItemBundle\GraphQL\Mutation\Item;

use App\Antti\FeedsAppItemBundle\Repository\ItemRepository;
use App\Antti\FeedsAppItemBundle\Repository\CategoryRepository;
use App\Antti\FeedsAppItemBundle\Entity\Item;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;

class Builder 
{
    /**
     * @var ItemRepository
     */
    private $itemRepository;
    
    /**
     * @var CategoryRepository 
     */
    private $categoryRepository;

    public function __construct(
       ItemRepository $itemRepository,
       CategoryRepository $categoryRepository
    ) {
        $this->itemRepository = $itemRepository;
        $this->categoryRepository = $categoryRepository;
    }
    
    public function build(ArgumentInterface $args): Item
    {
        $item = new Item();
        
        $rawArgs = $this->getRawArguments($args);
        
        $itemId = $this->getItemId($rawArgs);
        if ($itemId > 0) {
            $item = $this->itemRepository->get($itemId);
        }
        
        $this->assignArgumentValues($item, $rawArgs);
        
        return $item;
    }
    
    private function getRawArguments(ArgumentInterface $args): array 
    {
        $rawArgs = $args->getArrayCopy();
        return $rawArgs['input'] ?? [];
    }
    
    private function getItemId(array $rawArgs): int 
    {
        return (int)($rawArgs['id'] ?? 0);
    }
    
    private function assignArgumentValues(Item &$item, array $rawArgs) 
    {
        unset($rawArgs['id']);
        
        foreach ($rawArgs as $key => $value) {
            $func = 'set'.ucfirst($key);
            
            if ($key === 'categoryId') {
                $this->assignCategory($item, $value);
                continue;
            }
            call_user_func([$item, $func], $value); 
        }
    }
    
    private function assignCategory(Item &$item, $categoryId): void
    {
        $categoryId = (int)$categoryId;
        if ($categoryId > 0) {
            $item->setCategory($this->categoryRepository->get($categoryId));
        }
    }
}
