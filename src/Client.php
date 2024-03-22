<?php

declare(strict_types=1);

namespace Art4\Wegliphant;

use Art4\Wegliphant\Exception\UnexpectedResponseException;
use JsonException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;

final class Client
{
    public static function create(
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
    ): self {
        return new self($httpClient, $requestFactory);
    }

    private string $apiUrl = 'https://www.weg.li';

    private string $apiKey = '';

    private function __construct(
        private ClientInterface $httpClient,
        private RequestFactoryInterface $requestFactory,
    ) {}

    public function authenticate(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    /**
     * List all districts using the endpoint `GET /districts.json`
     *
     * @link https://www.weg.li/api
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface If an error happens while processing the request.
     * @throws UnexpectedResponseException If an error happens while processing the response.
     *
     * @return mixed[]
     */
    public function listDistricts(): array
    {
        $response = $this->sendJsonRequest('GET', '/districts.json');

        return $this->parseJsonResponseToArray($response, 200);
    }

    /**
     * Get one district by ZIP using the endpoint `GET /districts/<zip>.json`
     *
     * @link https://www.weg.li/api
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface If an error happens while processing the request.
     * @throws UnexpectedResponseException If an error happens while processing the response.
     *
     * @return mixed[]
     */
    public function getDistrictByZip(string $zip): array
    {
        $response = $this->sendJsonRequest('GET', '/api/districts/' . $zip);

        return $this->parseJsonResponseToArray($response, 200);
    }

    /**
     * List all charges using the endpoint `GET /charges.json`
     *
     * @link https://www.weg.li/api
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface If an error happens while processing the request.
     * @throws UnexpectedResponseException If an error happens while processing the response.
     *
     * @return mixed[]
     */
    public function listCharges(): array
    {
        $response = $this->sendJsonRequest('GET', '/api/charges');

        return $this->parseJsonResponseToArray($response, 200);
    }

    /**
     * List all notices for the authorized user using the endpoint `GET /api/notices`
     *
     * @link https://www.weg.li/api-docs/index.html#operations-notice-get_notices
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface If an error happens while processing the request.
     * @throws UnexpectedResponseException If an error happens while processing the response.
     *
     * @return mixed[]
     */
    public function listOwnNotices(): array
    {
        $response = $this->sendJsonRequest('GET', '/api/notices');

        return $this->parseJsonResponseToArray($response, 200);
    }

    /**
     * Get a notice by token for the authorized user using the endpoint `GET /api/notices/{token}`
     *
     * @link https://www.weg.li/api-docs/index.html#operations-notice-get_notices__token_
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface If an error happens while processing the request.
     * @throws UnexpectedResponseException If an error happens while processing the response.
     *
     * @return mixed[]
     */
    public function getNoticeByToken(string $token): array
    {
        $response = $this->sendJsonRequest('GET', '/api/notices/' . $token);

        return $this->parseJsonResponseToArray($response, 200);
    }

    /**
     * @throws \Psr\Http\Client\ClientExceptionInterface If an error happens while processing the request.
     */
    private function sendJsonRequest(
        string $method,
        string $path,
    ): ResponseInterface {
        $request = $this->requestFactory->createRequest($method, $this->apiUrl . $path);
        $request = $request->withHeader('Accept', 'application/json');

        if ($this->apiKey !== '') {
            $request = $request->withHeader('X-API-KEY', $this->apiKey);
        }

        return $this->httpClient->sendRequest($request);
    }

    /**
     * @throws UnexpectedResponseException If the response has the wrong status code.
     * @throws UnexpectedResponseException If the response has the wrong content type header.
     * @throws UnexpectedResponseException If an error happens while parsing the JSON response.
     *
     * @return mixed[]
     */
    private function parseJsonResponseToArray(ResponseInterface $response, int $expectedStatusCode): array
    {
        if ($response->getStatusCode() !== $expectedStatusCode) {
            throw UnexpectedResponseException::create(
                sprintf(
                    'Server replied with the status code %d, but %d was expected.',
                    $response->getStatusCode(),
                    $expectedStatusCode,
                ),
                $response,
            );
        }

        if (! str_starts_with($response->getHeaderLine('content-type'), 'application/json')) {
            throw UnexpectedResponseException::create('Server replied not with JSON content.', $response);
        }

        $responseBody = $response->getBody()->__toString();

        try {
            $data = json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $th) {
            throw UnexpectedResponseException::create('Response body contains no valid JSON: ' . $responseBody, $response, $th);
        }

        if (! is_array($data)) {
            throw UnexpectedResponseException::create('Response JSON does not contain an array: ' . $responseBody, $response);
        }

        return $data;
    }
}
