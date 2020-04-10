<?php
namespace App\Antti\FeedsAppItemImportBundle\Model\Item\Import\Transporter\Api;

class GuzzleHttpClient implements ConnectorInterface
{
    /**
     * @var \GuzzleHttp\Client 
     */
    private $client;
    
    public function __construct() 
    {
        $this->client = new \GuzzleHttp\Client();
    }

    public function getResponseData(\Psr\Http\Message\ResponseInterface $response): string 
    {
        return is_string($response->getBody()) ?: (string)$response->getBody();
    }

    public function getResponseErrorCode(\Psr\Http\Message\ResponseInterface $response): int 
    {
        return $response->getStatusCode();
    }

    public function getResponseErrorMessage(\Psr\Http\Message\ResponseInterface $response): string 
    {
        return $response->getReasonPhrase();
    }

    public function handle($method, $uri, array $options = []): \Psr\Http\Message\ResponseInterface 
    {
        return $this->client->request($method, $uri, $options);
    }

    public function isSuccess(\Psr\Http\Message\ResponseInterface $response): bool 
    {
        $code = $response->getStatusCode();
        return (200 <= $code && 300 > $code) || $code == 422;
    }
}
