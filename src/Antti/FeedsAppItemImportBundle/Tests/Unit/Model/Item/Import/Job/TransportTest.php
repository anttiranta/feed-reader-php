<?php
namespace App\Antti\FeedsAppItemImportBundle\Tests\Unit\Model\Item\Import\Job;

use \PHPUnit\Framework\TestCase;
use \App\Antti\FeedsAppItemImportBundle\Model\Item\Import\Job\Transport;
use \App\Antti\FeedsAppItemImportBundle\Model\Item\Import\Transporter;
use \App\Antti\FeedsAppItemImportBundle\Model\Item\Import\Job\Transport\Mapper\ItemMapper;

class TransportTest extends TestCase 
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $transporterMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $itemMapperMock;

    /**
     * @var Transport
     */
    private $model;

    /**
     * @var array 
     */
    private $validItemsData = [
        0 => [
            'title' => 'Recommended Desktop Feed Reader Software',
            'description' => 'Short description 1',
            'link' => 'http://www.feedforall.com/feedforall-partners.htm',
            'pubDate' => 'Tue, 26 Oct 2004 14:03:25 -0500',
            'comments' => '',
            'category' => ''
        ],
        1 => [
            'title' => 'RSS Solutions for Restaurants',
            'description' => 'Short description 2',
            'link' => 'http://www.feedforall.com/restaurant.htm',
            'category' => [
                'name' => 'Some category',
                'domain' => 'www.dmoz.com'
            ],
            'comments' => 'http://www.feedforall.com/forum',
            'pubDate' => 'Tue, 19 Oct 2004 11:09:11 -0400'
        ]
    ];
    
    /**
     * @var array 
     */
    private $invalidItemsData = [
        0 => [
            'description' => 'Short description 1',
            'link' => 'http://www.feedforall.com/feedforall-partners.htm',
            'pubDate' => 'Tue, 26 Oct 2004 14:03:25 -0500',
            'comments' => '',
            'category' => ''
        ],
        1 => [
            'title' => 'RSS Solutions for Restaurants',
            'description' => 'Short description 2',
            'link' => 'http://www.feedforall.com/restaurant.htm',
            'category' => [
                'name' => 'Some category',
                'domain' => 'www.dmoz.com'
            ],
            'comments' => 'http://www.feedforall.com/forum'
        ]
    ];

    /**
     * Set Up
     */
    protected function setUp() 
    {
        $this->transporterMock = $this->createMock(Transporter::class);
        $this->itemMapperMock = $this->createMock(ItemMapper::class);

        $this->model = new Transport(
            $this->transporterMock,
            $this->itemMapperMock
        );
    }

    /**
     * @param string $url
     * @param array $responseData
     * @param array $mappedItems
     * @param array $expectedItems
     * @return void
     * @dataProvider processDataProvider
     */
    public function testProcess(
        $url,
        $responseData,
        $mappedItems,
        $expectedItems
    ): void {
        $this->transporterMock->expects($this->once())
            ->method('readData')
            ->with($this->equalTo($url))
            ->will($this->returnValue($responseData));
        
        $this->itemMapperMock->expects($this->exactly(2))
            ->method('map')
            ->willReturnOnConsecutiveCalls($mappedItems[0], $mappedItems[1]);

        $data = $this->model->process($url);
        
        $this->assertEquals($expectedItems, $data);
    }

    /**
     * @return array
     */
    public function processDataProvider(): array 
    {
        require_once(dirname(__FILE__) . "/../../../../../_files/sample-xml-array.php");
        require_once(dirname(__FILE__) . "/../../../../../_files/sample-invalid-xml-array.php");

        return [
            [
                'url' => 'https://www.feedforall.com/sample-feed.xml',
                'responseData' => $sampleXmlArray,
                'mappedItems' => $this->validItemsData,
                'expectedItems' => $this->validItemsData,
            ],
            [
                'url' => 'https://www.feedforall.com/sample-feed.xml',
                'responseData' => $sampleInvalidXmlArray,
                'mappedItems' => $this->invalidItemsData,
                'expectedItems' => [],
            ],
        ];
    }
}
