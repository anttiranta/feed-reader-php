<?php
namespace App\Antti\FeedsAppItemBundle\Tests\Integration\Entity;

use App\Antti\FeedsAppItemBundle\Entity\Item;
use App\Antti\FeedsAppItemBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;

class ItemTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * @var Symfony\Component\Validator\Validator\ValidatorInterface
     */
    private $validator;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();
    }

    public function testItemSave(): void
    {
        $item = new Item();
        $item->setTitle("RSS Solutions for Restaurants")
            ->setPubDate(new \DateTime());
        
        $this->entityManager->persist($item);
        $this->entityManager->flush();
        
        $itemId = $item->getId();
        
        $foundItem = $this->entityManager->find(Item::class, $itemId);

        $this->assertEquals(
            "RSS Solutions for Restaurants", 
            $foundItem->getTitle(), 
            "Item title is invalid."
        );
    }
    
    public function testItemWithPubDateSave(): void
    {
        $pubDateStr = "2004-10-19T13:38:55.000-0400";
        $pubDate = new \DateTime($pubDateStr);

        $item = (new Item())
            ->setTitle("RSS Solutions for Restaurants")
            ->setPubDate($pubDate);
        
        $this->entityManager->persist($item);
        $this->entityManager->flush();
        
        $itemId = $item->getId();
        
        $foundItem = $this->entityManager->find(Item::class, $itemId);
        
        $this->assertEquals(
            $pubDate->getTimestamp(), 
            $foundItem->getPubDate()->getTimestamp(),
            "Item published date is invalid."
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
        
        $this->entityManager->persist($item);
        $this->entityManager->flush();
        
        $itemId = $item->getId();
        
        $foundItem = $this->entityManager->find(Item::class, $itemId);

        $this->assertEquals(
            "Computers/Software/Internet/Site Management/Content Management", 
            $foundItem->getCategory()->getName(),
            "Item category is invalid."
        );
    }
    
    public function testItemValidation(): void
    {
        $item = (new Item())
            ->setTitle("")
            ->setLink("dasdsads")
            ->setComments("djsfdhfd");
        
        $errors = $this->validator->validate($item);
        
        $this->assertEquals(3, count($errors), (string)$errors);
    }
    
    public function testItemSaveWithInvalidPubDate(): void
    {
        $this->expectException(\TypeError::class);
        
        $pubDateStr = "2004-10-19T13:38:55.000-0400";
        
        $item = (new Item())
            ->setTitle("RSS Solutions for Restaurants")
            ->setPubDate($pubDateStr);
        
        $this->entityManager->persist($item);
        $this->entityManager->flush();
    }
    
    public function testItemSaveWithoutPubDate(): void
    {
        $this->expectException(\Exception::class);
        
        $item = new Item();
        $item->setTitle("RSS Solutions for Restaurants");
        
        $this->entityManager->persist($item);
        $this->entityManager->flush();
    }
    
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}