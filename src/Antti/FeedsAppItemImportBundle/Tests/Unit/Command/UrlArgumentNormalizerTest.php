<?php
namespace App\Antti\FeedsAppItemImportBundle\Tests\Unit\Command;

use PHPUnit\Framework\TestCase;
use App\Antti\FeedsAppItemImportBundle\Command\UrlArgumentNormalizer;

class UrlArgumentNormalizerTest extends TestCase
{
    /**
     * @var UrlArgumentNormalizer 
     */
    private $normalizer;
    
    protected function setUp()
    {
        $this->normalizer = new UrlArgumentNormalizer();
    }
    
    public function testNormalizeUrlArgumentWithOneUrl(): void
    {
        $urls = $this->normalizer->normalizeUrlArgument("https://www.feedforall.com/sample.xml");
        
        $this->assertCount(1, $urls);
        $this->assertContains("https://www.feedforall.com/sample.xml", $urls); 
    }
    
    public function testNormalizeUrlArgumentWithUrlsSeparatedByCommas(): void
    {
        $urls = $this->normalizer->normalizeUrlArgument(
            "https://www.feedforall.com/sample.xml,https://www.feedforall.com/sample-feed.xml"
        );
        
        foreach (["https://www.feedforall.com/sample.xml", "https://www.feedforall.com/sample-feed.xml"] as $url) {
            $this->assertContains($url, $urls);
        }
        $this->assertCount(2, $urls);
    }
    
    public function testNormalizeUrlArgumentWithUrlArray(): void
    {
        $urls = $this->normalizer->normalizeUrlArgument(
            ["https://www.feedforall.com/sample.xml","https://www.feedforall.com/sample-feed.xml"]
        );
        
        foreach (["https://www.feedforall.com/sample.xml", "https://www.feedforall.com/sample-feed.xml"] as $url) {
            $this->assertContains($url, $urls);
        }
        $this->assertCount(2, $urls);
    }
}
