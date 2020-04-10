<?php
namespace App\Antti\FeedsAppItemImportBundle\Model\Item\Import\Job;

use Psr\Log\LoggerInterface;
use App\Antti\FeedsAppItemBundle\Entity\Item;
use App\Antti\FeedsAppItemBundle\Entity\Category;
use App\Antti\FeedsAppItemBundle\Repository\ItemRepository;
use App\Antti\FeedsAppItemImportBundle\Model\Item\Import\Job\Processor\Mapper\CategoryMapper;

class Processor extends AbstractJob
{
    /**
     * @var ItemRepository
     */
    private $itemRepository;
    
    /**
     * @var CategoryMapper 
     */
    private $categoryMapper;
    
    public function __construct(
        ItemRepository $itemRepository,
        CategoryMapper $categoryMapper,
        LoggerInterface $logger = null
    ) {
        parent::__construct($logger);
        
        $this->itemRepository = $itemRepository;
        $this->categoryMapper = $categoryMapper;
    }
    
    public function processAll(array $itemsData): bool
    {
        $failedCount = 0;
        
        foreach ($itemsData as $itemData) {
            $isSuccessful = $this->processItem($itemData);
            if (!$isSuccessful) {
                $failedCount++;
            }
        }
        
        if ($failedCount > 0) {
            $this->getLogger()->error(
                sprintf("Failed to process %d items", $failedCount)
            );
        }
        return $failedCount === 0;
    }
    
    public function processItem(array $itemData): bool
    {
        $item = new Item();
        
        try {
            $this->mapCategory($item, $itemData);
            $this->assignItemValues($item, $itemData);
        } catch (Exception $e) {
            return false;
        }
        
        return $this->saveItem($item);
    }
    
    private function mapCategory(Item &$item, array &$data): void
    {
        $category = null;
        
        if (isset($data['category'])) {
            $category = $data['category'];
            unset($data['category']);
        }
        
        if (!empty($category)) {
            $result = $this->categoryMapper->map($category);
            
            if ($result instanceof Category) {
                $item->setCategory($result);
            } else {
                $this->getLogger()->error(
                   "Category mapping failed", ['title' => $data['title']]
                );
            }
        }
    }
    
    private function assignItemValues(Item &$item, array $data): void
    {
        foreach ($data as $key => $value) {
            $func = 'set'.ucfirst($key);
            
            if ($key === 'pubDate') {
                $value = $this->convertPubDateToDateTime($value);
            }
            call_user_func([$item, $func], $value); 
        }
    }
    
    private function saveItem(Item $item): bool
    {
        try {
            $this->itemRepository->save($item);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    private function convertPubDateToDateTime(string $pubDateStr):? \DateTime
    {
        return !empty($pubDateStr) ? new \DateTime($pubDateStr) : null; 
    }
}