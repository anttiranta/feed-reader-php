<?php
namespace App\Antti\FeedsAppItemBundle\GraphQL\Mutation;

use App\Antti\FeedsAppItemBundle\Repository\ItemRepository;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Antti\FeedsAppItemBundle\GraphQL\Mutation\Item\Builder;
use App\Antti\FeedsAppCoreBundle\Exception\ClientSafeException;

class ItemMutation 
    extends \App\Antti\FeedsAppItemBundle\GraphQL\Mutation\Item
    implements MutationInterface 
{
    /**
     * @var ItemRepository
     */
    private $itemRepository;

    /**
     * @var ValidatorInterface
     */
    private $validator;
    
    /**
     * @var Builder 
     */
    private $builder;

    public function __construct(
       ItemRepository $itemRepository, 
       ValidatorInterface $validator,
       Builder $builder
    ) {
        $this->itemRepository = $itemRepository;
        $this->validator = $validator;
        $this->builder = $builder;
    }

    public function createItem(ArgumentInterface $args): \App\Antti\FeedsAppItemBundle\Entity\Item
    {
        $item = $this->builder->build($args);

        $errors = $this->validator->validate($item);
        if (count($errors) !== 0) {
            throw new ClientSafeException((string)$errors[0]);
        }
        
        try {
            return $this->itemRepository->save($item);
        } catch (Exception $ex) {
            throw new ClientSafeException($ex->getMessage());
        }
    }
    
    public function updateItem(ArgumentInterface $args): \App\Antti\FeedsAppItemBundle\Entity\Item 
    {
        $itemId = $this->getItemId($args);
        if (!$itemId) {
            throw new \InvalidArgumentException("Item identifier is missing.");
        }
        
        return $this->createItem($args);
    }
    
    public function removeItem(ArgumentInterface $args): int 
    {
        $itemId = $this->getItemId($args);
        if (!$itemId) {
            throw new \InvalidArgumentException("Item identifier is missing.");
        }
        
        try {
            $this->itemRepository->deleteByIdentifier($itemId);
        } catch (Exception $ex) {
            throw new ClientSafeException($ex->getMessage());
        }
        
        return $itemId;
    }
}
