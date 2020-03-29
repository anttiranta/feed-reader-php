<?php
namespace App\Antti\FeedsAppItemBundle\Tests\Unit\GraphQL\Resolver\Item\QueryBuilderProcessor;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use Doctrine\ORM\QueryBuilder;
use App\Antti\FeedsAppItemBundle\GraphQL\Resolver\Item\QueryBuilderProcessor\PaginationProcessor;
use App\Antti\FeedsAppItemBundle\Entity\Item;

class PaginationProcessorTest extends KernelTestCase 
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
     * @var PaginationProcessor 
     */
    private $paginationProcessor;

    protected function setUp(): void 
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->queryBuilder = $this->entityManager
            ->getRepository(Item::class)
            ->createQueryBuilder('item');
        
        $this->paginationProcessor = new PaginationProcessor();
    }

    public function testProcess(): void 
    {
        $args = $this->createMock(ArgumentInterface::class);
        $args->expects($this->any())
            ->method('offsetGet')
            ->will(
                $this->returnValueMap([
                    [PaginationProcessor::PAGE_SIZE, '1'],
                    [PaginationProcessor::CURRENT_PAGE, '2']
                ])
        );

        $this->paginationProcessor->process($args, $this->queryBuilder);

        $this->assertEquals(1, $this->queryBuilder->getMaxResults());
        $this->assertEquals(1, $this->queryBuilder->getFirstResult());
    }
    
    public function testProcessWithPageSizeBiggerThanMaximumAllowed(): void 
    {
        $args = $this->createMock(ArgumentInterface::class);
        $args->expects($this->any())
            ->method('offsetGet')
            ->will(
                $this->returnValueMap([
                    [PaginationProcessor::PAGE_SIZE, '300'],
                    [PaginationProcessor::CURRENT_PAGE, '2']
                ])
        );

        $this->paginationProcessor->process($args, $this->queryBuilder);

        $this->assertEquals(
            PaginationProcessor::MAX_ENTRIES_RETURN_AMOUNT, 
            $this->queryBuilder->getMaxResults()
        );
        $this->assertEquals(
            PaginationProcessor::MAX_ENTRIES_RETURN_AMOUNT, 
            $this->queryBuilder->getFirstResult()
        );
    }
    
    public function testProcessWithoutPageSize(): void 
    {
        $args = $this->createMock(ArgumentInterface::class);
        $args->expects($this->any())
            ->method('offsetGet')
            ->will(
                $this->returnValueMap([
                    [PaginationProcessor::PAGE_SIZE, null],
                    [PaginationProcessor::CURRENT_PAGE, '5']
                ])
            );

        $this->paginationProcessor->process($args, $this->queryBuilder);

        $this->assertEquals(
            PaginationProcessor::DEFAULT_ENTRIES_RETURN_AMOUNT, 
            $this->queryBuilder->getMaxResults()
        );
        $this->assertEquals(80, $this->queryBuilder->getFirstResult());
    }

    protected function tearDown(): void 
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
