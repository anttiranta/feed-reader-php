<?php
namespace App\Antti\FeedsAppItemBundle\Tests\Unit\GraphQL\Type;

use PHPUnit\Framework\TestCase;
use App\Antti\FeedsAppItemBundle\GraphQL\Type\DateTimeType;

class DateTimeTypeTest extends TestCase
{
    public function testSerialize(): void
    {
        $date = new \DateTime('Tue, 26 Oct 2004 14:01:01 -0500', new \DateTimeZone('Europe/London'));
        
        $dateStr = DateTimeType::serialize($date);
        
        $this->assertEquals('2004-10-26 14:01:01', $dateStr); // TODO: timezone!
    }
    
    public function testParseValue(): void
    {
        $dateStr = 'Tue, 26 Oct 2004 14:01:01 -0500';
        
        $date = DateTimeType::parseValue($dateStr);
        
        $this->assertEquals('2004-10-26 14:01:01', $date->format('Y-m-d H:i:s')); // TODO: timezone!
    }
}


