<?php
namespace App\Antti\FeedsAppItemImportBundle\Tests\Integration\Model\Item\Import\Job;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use \App\Antti\FeedsAppItemImportBundle\Model\Item\Import\Job\Processor;
use App\Antti\FeedsAppItemBundle\Entity\Item;
use App\Antti\FeedsAppItemBundle\Entity\Category;
use App\Antti\FeedsAppItemBundle\Repository\ItemRepository;
use App\Antti\FeedsAppItemImportBundle\Model\Item\Import\Job\Processor\Mapper\CategoryMapper;
use App\Antti\FeedsAppItemImportBundle\Model\Item\Import\Helper;

class ProcessorTest extends KernelTestCase 
{
    /**
     * @var Transport
     */
    private $model;

    /**
     * @var ItemRepository
     */
    private $itemRepository;
    
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * Set Up
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        
        // Configure variables
        $this->manager = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        
        $this->itemRepository = $this->entityManager
            ->getRepository(Item::class);
        
        $categoryRepository = $this->entityManager
            ->getRepository(Category::class);
        
        $this->model = new Processor(
            $this->itemRepository,
            new CategoryMapper($categoryRepository, new Helper())
        );
    }

    /**
     * @param array $data
     * @param Item $expectedItem
     * @return void
     * @dataProvider processAllDataProvider
     */
    public function testProcessAll(
        array $data,
        Item $expectedItem
    ): void {
        $this->assertTrue($this->model->processAll([$data]));
        
        $savedItem = $this->fetchItemByTitle($expectedItem->getTitle());

        $this->assertEquals($expectedItem->getTitle(), $savedItem->getTitle());
        $this->assertEquals($expectedItem->getDescription(), $savedItem->getDescription());
        $this->assertEquals($expectedItem->getLink(), $savedItem->getLink());
        $this->assertEquals(
            $expectedItem->getPubDate()->format(\DateTime::ATOM), 
            $savedItem->getPubDate()->format(\DateTime::ATOM)
        );
        
        if ($expectedItem->getCategory() !== null) {
            $this->assertEquals(
                $expectedItem->getCategory()->getName(), 
                $savedItem->getCategory()->getName()
            );
            $this->assertEquals(
                $expectedItem->getCategory()->getDomain(), 
                $savedItem->getCategory()->getDomain()
            );
        }
    }

    /**
     * @return array
     */
    public function processAllDataProvider(): array 
    {
        require_once(dirname(__FILE__) . "/../../../../../_files/sample-item-data-array.php");
        
        $mappedCategory = new Category();
        $mappedCategory->setName('Some category')
            ->setDomain('http://www.dmoz.com');
        
        $expectedItem = new Item();
        $expectedItem->setTitle('Recommended Desktop Feed Reader Software')
            ->setDescription('Short description 1')
            ->setLink('http://www.feedforall.com/feedforall-partners.htm')
            ->setPubDate(new \DateTime('2004-10-26T14:03:25-05:00'));
        
        $expectedItem2 = new Item();
        $expectedItem2->setTitle('RSS Solutions for Restaurants')
            ->setDescription('Short description 2')
            ->setLink('http://www.feedforall.com/restaurant.htm')
            ->setPubDate(new \DateTime('2004-10-19T11:09:11-04:00'))
            ->setComments('http://www.feedforall.com/forum')
            ->setCategory($mappedCategory);
        
        return [
            [
                'data' => $sampleItemsArray[0],
                'expectedItem' => $expectedItem,
                'mappedCategory' => null,
            ],
            [
                'data' => $sampleItemsArray[1],
                'expectedItem' => $expectedItem2,
                'mappedCategory' => $mappedCategory,
            ]
        ];
    }
    
    /**
     * Helper function to help with fetching item from DB. 
     * 
     * @param string $title
     * @return Item|null
     */
    private function fetchItemByTitle(string $title): ?Item 
    {
        $savedItem = $this->itemRepository
            ->findBy(['title' => $title]);
        
        if (is_array($savedItem)) {
            $savedItem = array_pop($savedItem);
        }
        return $savedItem;
    }
    
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
