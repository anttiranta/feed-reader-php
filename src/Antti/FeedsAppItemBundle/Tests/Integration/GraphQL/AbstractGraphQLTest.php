<?php
namespace App\Antti\FeedsAppItemBundle\Tests\Integration\GraphQL;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Antti\FeedsAppCoreBundle\Http\GraphQLClient\SymfonyWebTestGraphQLClient;

abstract class AbstractGraphQLTest extends WebTestCase 
{
    /** 
     * @var GraphQLClient\Client $client 
     */
    protected $client;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;
    
    /** 
     * @var ORMExecutor $executor 
     */
    private $executor;
    
    public function setUp() 
    {
        $this->client = new SymfonyWebTestGraphQLClient(static::createClient());
        $this->entityManager = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->executor = new ORMExecutor($this->entityManager, new ORMPurger());
    }
    
    protected function loadFixture($fixture)
    {
        $loader = new Loader();
        $fixtures = is_array($fixture) ? $fixture : [$fixture];
        foreach ($fixtures as $item) {
            $loader->addFixture($item);
        }
        $this->executor->execute($loader->getFixtures());
    }
    
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}