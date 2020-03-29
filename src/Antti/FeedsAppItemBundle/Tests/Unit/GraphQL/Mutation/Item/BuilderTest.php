<?php
namespace App\Antti\FeedsAppItemBundle\Tests\Unit\GraphQL\Mutation\Item;

use PHPUnit\Framework\TestCase;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use App\Antti\FeedsAppItemBundle\Repository\ItemRepository;
use App\Antti\FeedsAppItemBundle\Repository\CategoryRepository;
use App\Antti\FeedsAppItemBundle\Entity\Category;
use App\Antti\FeedsAppItemBundle\GraphQL\Mutation\Item\Builder;

class BuilderTest extends TestCase
{
    public function testBuild(): void
    {
        $args = $this->createMock(ArgumentInterface::class);
        $args->method('getArrayCopy')
            ->will($this->returnValue([
                'input' => [
                    'title' => 'RSS Resources',
                    'description' => 'Be sure to take a look at some of our favorite RSS Resources...<br>',
                    'link' => 'http://www.feedforall.com'
                ]
            ]));
        
        $builder = $this->getBuilderForArguments($args);
        
        $item = $builder->build($args);
        
        $this->assertEquals('RSS Resources', $item->getTitle());
        $this->assertEquals('Be sure to take a look at some of our favorite RSS Resources...<br>', $item->getDescription());
        $this->assertEquals('http://www.feedforall.com', $item->getLink());
        $this->assertNull($item->getCategory());
        $this->assertNull($item->getPubDate());
    }
    
    public function testBuildWithCategory(): void
    {
        $args = $this->createMock(ArgumentInterface::class);
        $args->method('getArrayCopy')
            ->will($this->returnValue([
                'input' => [
                    'title' => 'RSS Resources',
                    'categoryId' => 1
                ]
            ]));
        
        $builder = $this->getBuilderForArguments($args);
        
        $item = $builder->build($args);
        
        $this->assertNotNull($item->getCategory());
    }

    public function testBuildWithPubDate(): void
    {
        $pubDate = new \DateTime(
            'Tue, 26 Oct 2004 14:01:01 -0500', 
            new \DateTimeZone('Europe/London')
        );
        
        $args = $this->createMock(ArgumentInterface::class);
        $args->method('getArrayCopy')
            ->will($this->returnValue([
                'input' => [
                    'title' => 'RSS Resources',
                    'pubDate' => $pubDate
                ]
            ]));
        
        $builder = $this->getBuilderForArguments($args);
        
        $item = $builder->build($args);
        
        $this->assertEquals(
            "2004-10-26T14:01:01-0500",
            $item->getPubDate()->format(\DateTime::ISO8601)
        );
    }
    
    private function getBuilderForArguments(ArgumentInterface $args): Builder
    {
        $rawArgs = $args->getArrayCopy();
        
        $categoryId = $rawArgs['input']['categoryId'] ?? null;
        
        $itemRepository = $this->createMock(ItemRepository::class);
        $itemRepository
            ->expects($this->never())
            ->method('get');
        
        $categoryRepository = $this->createMock(CategoryRepository::class);
        $categoryRepository
            ->expects($categoryId ? $this->once() : $this->never())
            ->method('get')
            ->will($this->returnValue(new Category()));
        
        return new Builder($itemRepository, $categoryRepository);
    }
}