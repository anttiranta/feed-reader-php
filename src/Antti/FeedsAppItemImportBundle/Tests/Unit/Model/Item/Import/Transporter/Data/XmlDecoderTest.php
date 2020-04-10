<?php
namespace App\Antti\FeedsAppItemImportBundle\Tests\Unit\Model\Item\Import\Transporter\Data;

use PHPUnit\Framework\TestCase;
use App\Antti\FeedsAppItemImportBundle\Model\Item\Import\Transporter\Data\XmlDecoder;

class XmlDecoderTest extends TestCase 
{
    /**
     * @var XmlDecoder 
     */
    private $converter;

    protected function setUp() 
    {
        $this->converter = new XmlDecoder();
    }

    public function testDecode(): void 
    {
        require_once(dirname(__FILE__) . "/../../../../../../_files/sample-xml-string.php");

        $this->assertEquals(
            array('@version' => 2,
                'channel' => array(
                    'title' => 'Sample Feed',
                    'description' => 'Test description',
                    'item' => array(
                        0 => array(
                            'title' => 'Recommended Desktop Feed Reader Software',
                            'description' => 'Short description 1',
                            'link' => 'http://www.feedforall.com/feedforall-partners.htm',
                            'pubDate' => 'Tue, 26 Oct 2004 14:03:25 -0500',
                        ),
                        1 => array(
                            'title' => 'RSS Solutions for Restaurants',
                            'description' => 'Short description 2',
                            'link' => 'http://www.feedforall.com/restaurant.htm',
                            'category' => array(
                                '#' => 'Some category',
                                '@domain' => 'www.dmoz.com'
                            ),
                            'comments' => 'http://www.feedforall.com/forum',
                            'pubDate' => 'Tue, 19 Oct 2004 11:09:11 -0400'
                        )
                    )
                )
            ),
            $this->converter->decode($sampleXmlStr)
        ); 
    }
}
