<?php
namespace App\Antti\FeedsAppItemBundle\GraphQL\Mutation;

use Overblog\GraphQLBundle\Definition\ArgumentInterface;

abstract class Item 
{
    protected function getItemId(ArgumentInterface $args): int
    {
        $rawArgs = $args->getArrayCopy();
        
        $id = $rawArgs['input']['id'] ?? 0;
        if ($id === 0) {
            $id = $rawArgs['id'] ?? 0;
        }
        
        return (int)$id;
    }
}
