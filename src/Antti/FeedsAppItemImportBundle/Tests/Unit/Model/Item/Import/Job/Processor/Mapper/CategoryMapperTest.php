<?php
namespace App\Antti\FeedsAppItemImportBundle\Tests\Unit\Model\Item\Import\Job\Processor\Mapper;

use PHPUnit\Framework\TestCase;
use App\Antti\FeedsAppItemImportBundle\Model\Item\Import\Job\Processor\Mapper\CategoryMapper;
use App\Antti\FeedsAppItemBundle\Repository\CategoryRepository;
use App\Antti\FeedsAppItemBundle\Entity\Category;
use \App\Antti\FeedsAppItemImportBundle\Model\Item\Import\Helper;

class CategoryMapperTest extends TestCase 
{
    /**
     * @param array $data
     * @param Category $expectedCategory
     * @param Category|null $existingCategory
     * @return void
     * @dataProvider mapDataProvider
     */
    public function testMap(
        array $data,
        Category $expectedCategory,
        Category $existingCategory = null
    ): void {
        $categoryMapper = $this->getMapper($data, $existingCategory);
        
        $category = $categoryMapper->map($data);
        
        $this->assertEquals($expectedCategory, $category);
    }
    
    public function testMapWithoutDomainValue(): void 
    {
        $categoryRepository = $this->createMock(CategoryRepository::class);
        $helper = $this->createMock(Helper::class);
        
        $categoryMapper = new CategoryMapper($categoryRepository, $helper);
        
        $category = $categoryMapper->map([
            'name' => 'Some category'
        ]);
        $this->assertNull($category);
    }
    
    /**
     * @return array
     */
    public function mapDataProvider(): array 
    {
        $existingCategory = new Category();
        $existingCategory->setDomain('http://www.oldcategory.com')
            ->setName('Existing category');
        
        $expected = new Category();
        $expected->setDomain('http://www.dmoz.com')
            ->setName('Some category');
        
        $expected2 = new Category();
        $expected2->setDomain('http://www.google.com')
            ->setName('Test 2');
        
        return [
            [
                'data' => [
                    'name' => 'Some category',
                    'domain' => 'www.dmoz.com'
                ],
                'expectedCategory' => $expected,
                'existingCategory' => null,
            ],
            [
                'data' => [
                    'name' => 'Test 2',
                    'domain' => 'www.google.com'
                ],
                'expectedCategory' => $expected2,
                'existingCategory' => $existingCategory
            ],
        ];
    }
    
    private function getMapper(
        array $data,
        Category $category = null
    ): CategoryMapper {
        $normalizedUrl = 'http://' . $data['domain'];
        
        $categoryRepository = $this->createMock(CategoryRepository::class);
        $categoryRepository->expects($this->once())
            ->method('findByDomain')
            ->with($this->equalTo($normalizedUrl))
            ->will($this->returnValue($category));
        
        $helper = $this->createMock(Helper::class);
        $helper->expects($this->once())
            ->method('normalizeUrl')
            ->will($this->returnValue($normalizedUrl));
        
        return new CategoryMapper($categoryRepository, $helper);
    }
}
