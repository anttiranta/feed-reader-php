<?php
namespace App\Antti\FeedsAppItemBundle\Tests\Unit\GraphQL\Type;

use PHPUnit\Framework\TestCase;
use App\Antti\FeedsAppItemBundle\GraphQL\Type\DateTimeType;

class DateTimeTypeTest extends TestCase
{
    public function testSerialize(): void
    {
        $date = new \DateTime('2004-10-26T14:01:01-05:00');
        
        $dateStr = DateTimeType::serialize($date);
        
        $this->assertEquals('2004-10-26T14:01:01-05:00', $dateStr);
    }
    
    public function testParseValue(): void
    {
        $dateStr = '2004-10-26T14:01:01-05:00';
        
        $date = DateTimeType::parseValue($dateStr);
        
        $this->assertEquals($dateStr, $date->format(\DateTime::ATOM));
    }
}


