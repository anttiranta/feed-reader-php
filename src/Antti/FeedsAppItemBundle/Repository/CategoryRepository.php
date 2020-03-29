<?php
namespace App\Antti\FeedsAppItemBundle\Repository;

use App\Antti\FeedsAppItemBundle\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class CategoryRepository extends ServiceEntityRepository
{
    /**
     * @var Category[]
     */
    protected $instances = [];
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }
    
    public function get(int $categoryId): Category
    {
        if (!isset($this->instances[$categoryId])) {
            $category = $this->find($categoryId);
            if (!$category || !$category->getId()) {
                throw new \Exception('No category found for id ' . $categoryId);
            }
            $this->instances[$categoryId] = $category;
        }
        return $this->instances[$categoryId];
    }
    
    public function findByDomain(string $domain): ?Category
    {
        $result = $this->createQueryBuilder('category')
            ->where('category.domain = :domain')
            ->setParameter('domain', $domain)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
        
        return is_array($result) ? array_pop($result) : $result;
    }
}
