<?php
namespace App\Antti\FeedsAppItemBundle\Tests\Integration\GraphQL\Query;

use GraphQLClient\Field;
use GraphQLClient\Query;
use App\Antti\FeedsAppItemBundle\Tests\Integration\GraphQL\AbstractGraphQLTest;
use App\Antti\FeedsAppItemBundle\DataFixtures\ItemWithCategoryFixture;
use App\Antti\FeedsAppItemBundle\DataFixtures\MultipleItemsFixture;

class ItemListTest extends AbstractGraphQLTest 
{
    private static $KEY_TOTAL_COUNT = 'totalCount';
    private static $KEY_ITEMS = 'items';
    private static $KEY_ID = 'id';
    private static $KEY_TITLE = 'title';
    private static $KEY_LINK = 'link';
    private static $KEY_PUB_DATE = 'pubDate';
    private static $KEY_CATEGORY = 'category';
    private static $KEY_CATEGORY_NAME = 'name';
    
    public function testListItems(): void
    {
        $this->loadFixture(new MultipleItemsFixture());
        
        $query = new Query('items', ['limit' => 10], [
            new Field(self::$KEY_TOTAL_COUNT),
            new Field(self::$KEY_ITEMS, [
                new Field(self::$KEY_ID),
                new Field(self::$KEY_TITLE),
                new Field(self::$KEY_LINK),
                new Field(self::$KEY_PUB_DATE),
            ]),
        ]);

        $fields = $this->client->query($query)->getData();

        $this->client->assertGraphQlFields($fields, $query);
        
        $this->assertEquals("RSS Resources", $fields[self::$KEY_ITEMS][0][self::$KEY_TITLE]);
        $this->assertEquals("http://www.feedforall.com", $fields[self::$KEY_ITEMS][0][self::$KEY_LINK]);
        $this->assertEquals('2004-10-26 14:01:01', $fields[self::$KEY_ITEMS][0][self::$KEY_PUB_DATE]); // TODO: timezone!
        
        $this->assertEquals("Recommended Desktop Feed Reader Software", $fields[self::$KEY_ITEMS][1][self::$KEY_TITLE]);
        $this->assertEquals("http://www.feedforall.com/feedforall-partners.htm", $fields[self::$KEY_ITEMS][1][self::$KEY_LINK]);
        $this->assertEquals('2004-10-26 14:03:25', $fields[self::$KEY_ITEMS][1][self::$KEY_PUB_DATE]); // TODO: timezone!
        
        $this->assertEquals("Recommended Web Based Feed Reader Software", $fields[self::$KEY_ITEMS][2][self::$KEY_TITLE]);
        $this->assertEquals("http://www.feedforall.com/feedforall-partners.htm", $fields[self::$KEY_ITEMS][2][self::$KEY_LINK]);
        $this->assertEquals('2004-10-26 14:06:44', $fields[self::$KEY_ITEMS][2][self::$KEY_PUB_DATE]); // TODO: timezone!
        
        $this->assertEquals(3, (int)$fields[self::$KEY_TOTAL_COUNT]);
    }
    
    public function testFilterWithItemTitle(): void
    {
        $this->loadFixture(new MultipleItemsFixture());
        
        $query = new Query('items', ['limit' => 10, 'filter' => ['title' => ['like' => "Recommended Desktop"]]], [
            new Field(self::$KEY_TOTAL_COUNT),
            new Field(self::$KEY_ITEMS, [
                new Field(self::$KEY_ID),
                new Field(self::$KEY_TITLE),
                new Field(self::$KEY_LINK),
                new Field(self::$KEY_PUB_DATE),
            ]),
        ]);
        
        $fields = $this->client->query($query)->getData();

        $this->client->assertGraphQlFields($fields, $query);
        
        $this->assertCount(1, $fields[self::$KEY_ITEMS]);
        
        $this->assertEquals("Recommended Desktop Feed Reader Software", $fields[self::$KEY_ITEMS][0][self::$KEY_TITLE]);
        $this->assertEquals("http://www.feedforall.com/feedforall-partners.htm", $fields[self::$KEY_ITEMS][0][self::$KEY_LINK]);
        
        $this->assertEquals(1, (int)$fields[self::$KEY_TOTAL_COUNT]);
    }
    
    public function testFilterWithCategoryName(): void
    {
        $this->loadFixture(new ItemWithCategoryFixture());
        
        $query = new Query('items', ['limit' => 5, 'p' => 1, 'filter' => 
            ['categoryName' => ['like' => "A really unique category"]]], [
            new Field(self::$KEY_TOTAL_COUNT),
            new Field(self::$KEY_ITEMS, [
                new Field(self::$KEY_ID),
                new Field(self::$KEY_TITLE),
                new Field(self::$KEY_LINK),
                new Field(self::$KEY_PUB_DATE),
                new Field(self::$KEY_CATEGORY, [
                    new Field(self::$KEY_ID),
                    new Field(self::$KEY_CATEGORY_NAME),
                ]),
            ]),
        ]);
        
        $fields = $this->client->query($query)->getData();

        $this->client->assertGraphQlFields($fields, $query);
        
        $this->assertCount(1, $fields[self::$KEY_ITEMS]);
        $this->assertEquals(1, (int)$fields[self::$KEY_TOTAL_COUNT]);
        
        $this->assertEquals(
            "Something", 
            $fields[self::$KEY_ITEMS][0][self::$KEY_TITLE]
        );
        $this->assertEquals(
            "http://www.somethingelse.com", 
            $fields[self::$KEY_ITEMS][0][self::$KEY_LINK]
        );
        
        $this->assertEquals(
            "A really unique category name", 
            $fields[self::$KEY_ITEMS][0][self::$KEY_CATEGORY][self::$KEY_CATEGORY_NAME]
        );
    }
}
