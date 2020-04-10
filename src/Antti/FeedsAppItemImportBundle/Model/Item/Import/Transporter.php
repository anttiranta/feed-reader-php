<?php
namespace App\Antti\FeedsAppItemImportBundle\Model\Item\Import;

use \Psr\Http\Message\ResponseInterface;
use App\Antti\FeedsAppItemImportBundle\Model\Item\Import\Transporter\Api\ConnectorInterface;
use App\Antti\FeedsAppItemImportBundle\Model\Item\Import\Transporter\Data\XmlDecoder;

class Transporter
{
    /**
     * @var App\Antti\FeedsAppItemImportBundle\Model\Item\Import\Transporter\Api\ConnectorInterface
     */
    private $connector;

    /**
     * @var XmlDecoder
     */
    private $converter;
    
    public function __construct(
        ConnectorInterface $connector,
        XmlDecoder $converter 
    ) {
        $this->connector = $connector;
        $this->converter = $converter;
    }
    
    /**
     * Retrieve data read from an API call
     *
     * @param string $fullUri
     * @return array
     */
    public function readData(string $fullUri): array
    {
        $method = 'get';
        
        $response = $this->connector->handle($method, $fullUri, []);
        $this->validateResponse($response);

        $responseData = $this->connector->getResponseData($response);

        return $responseData ? $this->converter->decode($responseData) : []; 
    }
    
    /**
     * Validate response
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @throws \Exception
     */
    private function validateResponse(ResponseInterface $response): void
    {
        if (!$this->connector->isSuccess($response)) {
            throw new \Exception(sprintf(
                'Responded with an error %s (%s).',
                $this->connector->getResponseErrorCode($response),
                $this->connector->getResponseErrorMessage($response)
            ));
        }
    }
}
