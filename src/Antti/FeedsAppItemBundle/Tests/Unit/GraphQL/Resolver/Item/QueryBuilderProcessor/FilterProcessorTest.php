<?php
namespace App\Antti\FeedsAppItemBundle\Tests\Unit\GraphQL\Resolver\Item\QueryBuilderProcessor;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use Doctrine\ORM\QueryBuilder;
use App\Antti\FeedsAppItemBundle\GraphQL\Resolver\Item\QueryBuilderProcessor\FilterProcessor;
use App\Antti\FeedsAppItemBundle\Entity\Item;

class FilterProcessorTest extends KernelTestCase 
{
    /**
     * @var QueryBuilder 
     */
    private $queryBuilder;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;
    
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

    protected function setUp(): void 
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->queryBuilder = $this->entityManager
            ->getRepository(Item::class)
            ->createQueryBuilder('item');
        
        $this->filterProcessor = new FilterProcessor(
            $this->allowedFilters, 
            $this->fieldMapping
        );
    }

    public function testProcessWithItemTitle(): void 
    {
        $args = $this->createMock(ArgumentInterface::class);
        $args->expects($this->any())
            ->method('offsetGet')
            ->will($this->returnValueMap([[
                FilterProcessor::FILTER, 
                ['title' => ['like' => "Recommended Desktop"]]
            ]])
        );

        $this->filterProcessor->process($args, $this->queryBuilder);

        $this->assertEquals("Recommended Desktop%", $this->queryBuilder->getParameter('title')->getValue());
        $this->assertCount(0, $this->queryBuilder->getDQLPart('join'));
        $this->assertEquals(
            ['item.title like :title'], 
            $this->queryBuilder->getDQLPart('where')->getParts()        
        );
    }
    
    public function testProcessWithCategoryName(): void 
    {
        $args = $this->createMock(ArgumentInterface::class);
        $args->expects($this->any())
            ->method('offsetGet')
            ->will($this->returnValueMap([[
                FilterProcessor::FILTER, 
                ['categoryName' => ['like' => "Specific category"]]
            ]])
        );

        $this->filterProcessor->process($args, $this->queryBuilder);

        $this->assertEquals(
            "Specific category%", 
            $this->queryBuilder->getParameter('categoryName')->getValue()
        );
        $this->assertCount(1, $this->queryBuilder->getDQLPart('join'));
        $this->assertEquals(
            ['cat.name like :categoryName'], 
            $this->queryBuilder->getDQLPart('where')->getParts()        
        );
    }
    
    public function testWithInvalidFilter(): void
    {
        $args = $this->createMock(ArgumentInterface::class);
        $args->expects($this->any())
            ->method('offsetGet')
            ->will($this->returnValueMap([[
                FilterProcessor::FILTER, 
                ['test' => ['like' => "Test test"]]
            ]])
        );

        $this->filterProcessor->process($args, $this->queryBuilder);

        $this->assertCount(0, $this->queryBuilder->getParameters());
        $this->assertCount(0, $this->queryBuilder->getDQLPart('join'));
        $this->assertNull($this->queryBuilder->getDQLPart('where'));
    }

    protected function tearDown(): void 
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
