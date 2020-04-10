<?php
namespace App\Antti\FeedsAppItemImportBundle\Tests\Unit\Model\Item\Import\Job\Transport\Mapper;

use PHPUnit\Framework\TestCase;
use App\Antti\FeedsAppItemImportBundle\Model\Item\Import\Job\Transport\Mapper\ItemMapper;

class ItemMapperTest extends TestCase {

    /**
     * @var ItemMapper 
     */
    private $itemMapper;

    protected function setUp() 
    {
        $this->itemMapper = new ItemMapper();
    }

    public function testMapWithSimpleItem(): void 
    {
        $mappedItem = $this->itemMapper->map([
            'title' => 'Recommended Desktop Feed Reader Software',
            'description' => 'Short description 1',
            'link' => 'http://www.feedforall.com/feedforall-partners.htm',
            'pubDate' => 'Tue, 26 Oct 2004 14:03:25 -0500',
        ]);
        
        $this->assertEquals(
            [
                'title' => 'Recommended Desktop Feed Reader Software',
                'description' => 'Short description 1',
                'link' => 'http://www.feedforall.com/feedforall-partners.htm',
                'pubDate' => 'Tue, 26 Oct 2004 14:03:25 -0500',
                'comments' => '',
                'category' => ''
            ],
            $mappedItem
        );
    }

    /**
     * @return void
     */
    public function testMapWithItemThatHasCategory(): void 
    {
        $mappedItem = $this->itemMapper->map([
            'title' => 'RSS Solutions for Restaurants',
            'description' => 'Short description 2',
            'link' => 'http://www.feedforall.com/restaurant.htm',
            'category' => [
                '#' => 'Some category',
                '@domain' => 'www.dmoz.com'
            ],
            'comments' => 'http://www.feedforall.com/forum',
            'pubDate' => 'Tue, 19 Oct 2004 11:09:11 -0400'
        ]);
        
        $this->assertEquals(
            [
                'title' => 'RSS Solutions for Restaurants',
                'description' => 'Short description 2',
                'link' => 'http://www.feedforall.com/restaurant.htm',
                'category' => [
                    'name' => 'Some category',
                    'domain' => 'www.dmoz.com'
                ],
                'comments' => 'http://www.feedforall.com/forum',
                'pubDate' => 'Tue, 19 Oct 2004 11:09:11 -0400'
            ],
            $mappedItem
        );
    }
    
    public function testMapWithItemMissingTitleAndPubDate(): void 
    {
        $mappedItem = $this->itemMapper->map([
            'description' => 'Short description 1',
            'link' => 'http://www.feedforall.com/feedforall-partners.htm',
        ]);
        
        $this->assertEquals(
            [
                'description' => 'Short description 1',
                'link' => 'http://www.feedforall.com/feedforall-partners.htm',
                'comments' => '',
                'category' => '',
                'pubDate' => ''
            ],
            $mappedItem
        );
    }
}
