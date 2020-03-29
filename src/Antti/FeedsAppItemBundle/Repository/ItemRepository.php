<?php
namespace App\Antti\FeedsAppItemBundle\Repository;

use App\Antti\FeedsAppItemBundle\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class ItemRepository extends ServiceEntityRepository
{
    /**
     * @var Item[]
     */
    protected $instances = [];
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }
    
    public function get(int $itemId) : Item
    {
        if (!isset($this->instances[$itemId])) {
            $item = $this->find($itemId);
            if (!$item || !$item->getId()) {
                throw new \Exception('No item found for id ' . $itemId);
            }
            $this->instances[$itemId] = $item;
        }
        return $this->instances[$itemId];
    }
 
    public function delete(Item $item): void
    {
        try {
            $this->_em->remove($item);
            $this->_em->flush();
        } catch (\Exception $e) {
            throw new \Exception(
                sprintf(
                    'Cannot delete item with id %d',
                    $item->getId()
                ),
                $e->getCode(),
                $e
            );
        }
    }
    
    public function deleteByIdentifier(int $itemId): void
    {
        $item = $this->get($itemId);
        $this->delete($item);
    }
    
    public function save(Item $item): Item
    {
        $isNew = (bool)!$item->getId();
                
        try {
            if (!$item->getId()) {
                $this->_em->persist($item);
            }
            $this->_em->flush();
        } catch (\Exception $e) {
            throw new \Exception(
                'Could not save item: ' . (int)$item->getId(),
                $e->getCode(),
                $e
            );
        }
        
        if (!$isNew) {
            unset($this->instances[$item->getId()]);
        }
        return $this->get($item->getId());
    }
}
