<?php
namespace App\Antti\FeedsAppItemImportBundle\Model\Item\Import\Transporter\Data;

use \Symfony\Component\Serializer\Encoder\XmlEncoder;

class XmlDecoder
{
    /**
     * @var Symfony\Component\Serializer\Encoder\XmlEncoder
     */
    private $serializer;

    /**
     * @param Symfony\Component\Serializer\Encoder\XmlEncoder $serializer
     */
    public function __construct(XmlEncoder $serializer = null) {
        $this->serializer = $serializer != null 
            ? $serializer 
            : new XmlEncoder();
    }
    
    /**
     * @param string $data
     * @return array
     */
    public function decode(string $data): array
    {
        return $this->serializer->decode($data, '');
    }
}
