<?php
namespace App\Antti\FeedsAppItemImportBundle\Model\Item\Import\Transporter\Api;

use Psr\Http\Message\ResponseInterface;

interface ConnectorInterface
{
    /**
     * @param string $method
     * @param string|\Psr\Http\Message\UriInterface $uri
     * @param array $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle($method, $uri, array $options = []): ResponseInterface;

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return bool
     */
    public function isSuccess(ResponseInterface $response): bool;

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return int
     */
    public function getResponseErrorCode(ResponseInterface $response): int;

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return string
     */
    public function getResponseErrorMessage(ResponseInterface $response): string;

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return string
     */
    public function getResponseData(ResponseInterface $response): string;
}