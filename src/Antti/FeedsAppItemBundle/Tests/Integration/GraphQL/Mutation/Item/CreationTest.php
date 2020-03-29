<?php
namespace App\Antti\FeedsAppItemBundle\Tests\Integration\GraphQL\Mutation\Item;

use GraphQLClient\Field;
use GraphQLClient\Query;
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
                'pubDate' => 'Tue, 26 Oct 2004 14:01:01 -0500'
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
    }
}
