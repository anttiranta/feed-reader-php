<?php
namespace App\Antti\FeedsAppItemBundle\Tests\Integration\Entity;

use App\Antti\FeedsAppItemBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;

class CategoryTest extends KernelTestCase
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

    public function testCategorySaveWithInvalidDomain(): void
    {
        $category = new Category();
        $category->setName("Computers/Software/Internet/Site Management/Content Management")
                ->setDomain("www.dmoz.com");
        
        $errors = $this->validator->validate($category);
        
        $this->assertEquals(1, count($errors), (string)$errors);
    }
    
    public function testSavingMultipleCategoriesWithSameDomain(): void
    {
        $this->expectException(\Exception::class);
        
        $category = new Category();
        $category->setName("Category 1")
                ->setDomain("http://www.dmoz.com");
        
        $category2 = new Category();
        $category2->setName("Category 2")
                ->setDomain("http://www.dmoz.com");
        
        $this->entityManager->persist($category);
        $this->entityManager->persist($category2);
        $this->entityManager->flush();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}