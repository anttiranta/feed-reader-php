<?php
namespace App\Antti\FeedsAppItemBundle\GraphQL\Type;

use GraphQL\Language\AST\Node;

class DateTimeType
{
    /**
     * @param \DateTime $value
     *
     * @return string
     */
    public static function serialize(\DateTime $value)
    {
        return $value->format('Y-m-d H:i:s'); // TODO: timezone?
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public static function parseValue($value)
    {
        return new \DateTime($value); // TODO: timezone?
    }

    /**
     * @param Node $valueNode
     *
     * @return string
     */
    public static function parseLiteral($valueNode)
    {
        return \DateTime($valueNode->value); // TODO: timezone?
    }
}
