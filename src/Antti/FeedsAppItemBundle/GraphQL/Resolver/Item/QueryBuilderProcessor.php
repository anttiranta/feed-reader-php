<?php
namespace App\Antti\FeedsAppItemBundle\GraphQL\Resolver\Item;

use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use Doctrine\ORM\QueryBuilder;

class QueryBuilderProcessor implements QueryBuilderProcessorInterface
{
    private $processors;

    public function __construct(
        array $processors
    ) {
        $this->processors = $processors;
    }

    public function process(ArgumentInterface $args, QueryBuilder &$queryBuilder): void
    {
        foreach ($this->processors as $name => $processor) {
            if (!($processor instanceof QueryBuilderProcessorInterface)) {
                throw new \InvalidArgumentException(
                    sprintf('Processor %s must implement %s interface.', $name, QueryBuilderProcessorInterface::class)
                );
            }
            $processor->process($args, $queryBuilder);
        }
    }
}
