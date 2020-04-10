<?php
namespace App\Antti\FeedsAppItemImportBundle\Model\Item\Import\Job\Processor\Mapper;

use \App\Antti\FeedsAppItemBundle\Entity\Category;
use \App\Antti\FeedsAppItemBundle\Repository\CategoryRepository;
use \App\Antti\FeedsAppItemImportBundle\Model\Item\Import\Helper;

class CategoryMapper 
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    
    /**
     * @var Helper 
     */
    private $helper;
    
    public function __construct(
       CategoryRepository $categoryRepository,
       Helper $helper
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->helper = $helper;
    }
    
    public function map(array $categoryData): ?Category
    {
        if(isset($categoryData['domain'])) {
            $domain = $this->helper->normalizeUrl($categoryData['domain']);
            
            // replace domain url with normalized url
            $categoryData['domain'] = $domain; 
            
            $category = $this->categoryRepository->findByDomain($domain);
            
            if ($category === null) {
                $category = $this->prepareNewCategory($categoryData);
            } else {
                $this->assignCategoryValues($category, $categoryData);
            }
            return $category;
        }
        
        return null;
    }
    
    private function prepareNewCategory(array $categoryData): Category
    {
        $category = new Category();
        $this->assignCategoryValues($category, $categoryData);
        
        return $category;
    }
    
    private function assignCategoryValues(
        Category &$category, 
        array $categoryData
    ): void {
        $category->setDomain($categoryData['domain'])
            ->setName($categoryData['name'] ?? null);
    }
}
