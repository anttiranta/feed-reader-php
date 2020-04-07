<?php
namespace App\Antti\FeedsAppItemBundle\Tests\Integration\GraphQL\Mutation\Item;

use GraphQLClient\Field;
use GraphQLClient\Query;
use GraphQLClient\Variable;
use App\Antti\FeedsAppItemBundle\Entity\Item;
use App\Antti\FeedsAppItemBundle\Tests\Integration\GraphQL\AbstractGraphQLTest;

class CreationTest extends AbstractGraphQLTest 
{
    public function testItemCreationSuccess(): void
    {
        $itemTitle = "This item was created with GraphQL mutation";
        $itemLink = 'http://www.feedforall.com';
        
        $params = [
            'input' => [
                'title' => $itemTitle,
                'link' => $itemLink,
                'pubDate' => new Variable(
                    'pubDate', "2004-10-26T14:01:01-03:00", 'DateTime!'
                )
            ]
        ];
        
        $query = new Query('createItem', $params, [
            new Field('id'),
        ]);

        $fields = $this->client->mutate($query)->getData();

        $this->client->assertGraphQlFields($fields, $query);
        
        $item = $this->entityManager
                ->getRepository(Item::class)
                ->findBy(['title' => $itemTitle]);
        $item = is_array($item) ? array_pop($item) : $item;
        
        $this->assertEquals($itemTitle, $item->getTitle());
        $this->assertEquals($itemLink, $item->getLink());
        $this->assertEquals(
            "2004-10-26T14:01:01-03:00",
            $item->getPubDate()->format(\DateTime::ATOM)
        );
    }
}
