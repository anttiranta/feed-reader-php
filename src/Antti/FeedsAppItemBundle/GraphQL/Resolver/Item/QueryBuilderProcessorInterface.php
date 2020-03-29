<?php
namespace App\Antti\FeedsAppItemBundle\GraphQL\Resolver\Item;

use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use Doctrine\ORM\QueryBuilder;

interface QueryBuilderProcessorInterface
{
    public function process(ArgumentInterface $args, QueryBuilder &$queryBuilder): void;
}