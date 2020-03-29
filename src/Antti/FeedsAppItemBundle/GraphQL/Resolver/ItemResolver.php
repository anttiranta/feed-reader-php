<?php
namespace App\Antti\FeedsAppItemBundle\GraphQL\Resolver;

use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use App\Antti\FeedsAppItemBundle\Entity\Item;
use App\Antti\FeedsAppItemBundle\Repository\ItemRepository;

class ItemResolver implements ResolverInterface
{
    /**
     * @var ItemRepository
     */
    private $itemRepository;
    
    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }
    
    public function resolve(int $id) :Item
    {
        return $this->itemRepository->get($id);
    }
}