<?php
namespace App\Antti\FeedsAppItemBundle\GraphQL\Resolver\Item\QueryBuilderProcessor;

use App\Antti\FeedsAppItemBundle\GraphQL\Resolver\Item\QueryBuilderProcessorInterface;
use App\Antti\FeedsAppItemBundle\GraphQL\Resolver\Item\QueryBuilderProcessor;

class ProcessorFactory
{
    public function create(array $data): QueryBuilderProcessorInterface
    {
        return new QueryBuilderProcessor($data);
    }
}
