<?php
namespace App\Antti\FeedsAppItemBundle\Tests\Integration\Repository;

use App\Antti\FeedsAppItemBundle\DataFixtures\ItemFixture;
use App\Antti\FeedsAppItemBundle\Entity\Item;
use App\Antti\FeedsAppItemBundle\Entity\Category;

class ItemRepositoryTest extends AbstractRepositoryTest
{
    public function testGetItemNotFound(): void
    {
        $this->expectException(\Exception::class);
        
        $foundItem = $this->entityManager
            ->getRepository(Item::class)
            ->get(1);
    }
        
    public function testSave(): void
    {
        $item = new Item();
        $item->setTitle("RSS Solutions for Restaurants")
            ->setPubDate(new \DateTime());
        
        $savedItem = $this->entityManager
            ->getRepository(Item::class)
            ->save($item);

        $this->assertEquals(
            "RSS Solutions for Restaurants",
            $savedItem->getTitle(), 
            "Item title is invalid."
        );
    }
    
    public function testItemWithCategorySave(): void
    {
        $item = new Item();
        $item->setTitle("RSS Solutions for Restaurants")
            ->setPubDate(new \DateTime());
        
        $category = new Category();
        $category->setName("Computers/Software/Internet/Site Management/Content Management")
                ->setDomain("http://www.dmoz.com");
        
        $item->setCategory($category);
        
        $savedItem = $this->entityManager
            ->getRepository(Item::class)
            ->save($item);

        $this->assertGreaterThan(
            0, 
            $savedItem->getId(),
            "Item didn't get saved properly."
        );
        
        $this->assertEquals(
            "Computers/Software/Internet/Site Management/Content Management", 
            $savedItem->getCategory()->getName(),
            "Item category is invalid."
        );
        
        $items = $savedItem->getCategory()->getItems();
        $this->assertCount(
            1, 
            $items,
            "Item was not saved for category"
        );
        
        $itemInCategory = $items->first();
        
        $this->assertEquals(
            "RSS Solutions for Restaurants", 
            $itemInCategory->getTitle() ,
            "Item was not set to category correctly."
        );
    }
    
    public function testItemUpdate(): void
    {
        $this->loadFixture(new ItemFixture());
        
        $repository = $this->entityManager->getRepository(Item::class);
        
        $item = $repository->findBy(['title' => "RSS Solutions for Restaurants"]);
        if (is_array($item)) {
            $item = array_pop($item);
        }
        $item->setTitle("new title");
        
        $updatedItem = $repository->save($item);
        
        $this->assertEquals(
            "new title", 
            $updatedItem->getTitle(),
            "Item was not updated."
        );
        
        $this->assertNotNull(
            $updatedItem->getUpdatedAt(),
            "Updated at time has not been set"
        );
    }
    
    public function testItemDelete(): void
    {
        $this->loadFixture(new ItemFixture());
        
        $repository = $this->entityManager->getRepository(Item::class);
        
        $item = $repository->findBy(['link' => "http://www.google.com"]);
        if (is_array($item)) {
            $item = array_pop($item);
        }
        
        $itemId = $item->getId();
        
        $this->assertNotNull($itemId);
        $this->assertGreaterThan(0, $itemId);
        
        $repository->delete($item);
     
        $foundItem = $repository->find($itemId);
        $this->assertNull($foundItem);
    }
}