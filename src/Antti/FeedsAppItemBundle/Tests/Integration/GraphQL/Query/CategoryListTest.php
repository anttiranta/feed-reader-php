<?php
namespace App\Antti\FeedsAppItemBundle\Tests\Integration\GraphQL\Query;

use GraphQLClient\Field;
use GraphQLClient\Query;
use App\Antti\FeedsAppItemBundle\Tests\Integration\GraphQL\AbstractGraphQLTest;
use App\Antti\FeedsAppItemBundle\DataFixtures\MultipleCategoriesFixture;

class CategoryListTest extends AbstractGraphQLTest
{
    private static $KEY_CATEGORIES = 'categories';
    private static $KEY_ID = 'id';
    private static $KEY_NAME = 'name';
    private static $KEY_DOMAIN = 'domain';
    
    public function testListCategories(): void
    {
        $this->loadFixture(new MultipleCategoriesFixture());
        
        $query = new Query('categories', ['limit' => 100], [
            new Field(self::$KEY_CATEGORIES, [
                new Field(self::$KEY_ID),
                new Field(self::$KEY_NAME),
                new Field(self::$KEY_DOMAIN),
            ]),
        ]);

        $fields = $this->client->query($query)->getData();

        $this->client->assertGraphQlFields($fields, $query);
        
        $this->assertEquals("First category", $fields[self::$KEY_CATEGORIES][0][self::$KEY_NAME]);
        $this->assertEquals("http://www.first.com", $fields[self::$KEY_CATEGORIES][0][self::$KEY_DOMAIN]);
        
        $this->assertEquals("Something else", $fields[self::$KEY_CATEGORIES][1][self::$KEY_NAME]);
        $this->assertEquals("http://www.somethingelse.com", $fields[self::$KEY_CATEGORIES][1][self::$KEY_DOMAIN]);
        
        $this->assertEquals("Third category", $fields[self::$KEY_CATEGORIES][2][self::$KEY_NAME]);
        $this->assertEquals("http://www.thirdcategory.com", $fields[self::$KEY_CATEGORIES][2][self::$KEY_DOMAIN]);
    }
}
