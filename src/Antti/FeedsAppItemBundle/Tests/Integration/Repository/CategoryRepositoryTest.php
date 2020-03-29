<?php
namespace App\Antti\FeedsAppItemBundle\Tests\Integration\Repository;

use App\Antti\FeedsAppItemBundle\DataFixtures\CategoryFixture;
use App\Antti\FeedsAppItemBundle\Entity\Category;

class CategoryRepositoryTest extends AbstractRepositoryTest
{
    public function testGetCategoryNotFound(): void
    {
        $this->expectException(\Exception::class);
        
        $foundCategory = $this->entityManager
            ->getRepository(Category::class)
            ->get(1);
    }

    public function testSearchByCategoryDomain(): void
    {
        $this->loadFixture(new CategoryFixture());
        
        $category = $this->entityManager
            ->getRepository(Category::class)
            ->findByDomain('http://www.dmoz.com');

        $this->assertNotNull($category);
    }
}