<?php

declare(strict_types=1);

namespace Art4\Wegliphant;

use Exception;
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

    /**
     * List all districts using the endpoint `GET /districts.json`
     *
     * @link https://www.weg.li/api
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface If an error happens while processing the request.
     * @throws \Exception If an error happens while processing the response.
     *
     * @return mixed[]
     */
    public function listDistricts(): array
    {
        $response = $this->sendJsonRequest('GET', '/districts.json');

        $this->ensureJsonResponse($response, 200);

        return $this->parseJsonResponseToArray($response);
    }

    /**
     * Get one district by ZIP using the endpoint `GET /districts/<zip>.json`
     *
     * @link https://www.weg.li/api
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface If an error happens while processing the request.
     * @throws \Exception If an error happens while processing the response.
     *
     * @return mixed[]
     */
    public function getDistrictByZip(string $zip): array
    {
        $response = $this->sendJsonRequest('GET', '/districts/' . $zip . '.json');

        $this->ensureJsonResponse($response, 200);

        return $this->parseJsonResponseToArray($response);
    }

    /**
     * List all charges using the endpoint `GET /charges.json`
     *
     * @link https://www.weg.li/api
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface If an error happens while processing the request.
     * @throws \Exception If an error happens while processing the response.
     *
     * @return mixed[]
     */
    public function listCharges(): array
    {
        $response = $this->sendJsonRequest('GET', '/charges.json');

        $this->ensureJsonResponse($response, 200);

        return $this->parseJsonResponseToArray($response);
    }

    private function __construct(
        private ClientInterface $httpClient,
        private RequestFactoryInterface $requestFactory,
    ) {}

    /**
     * @throws \Psr\Http\Client\ClientExceptionInterface If an error happens while processing the request.
     */
    private function sendJsonRequest(
        string $method,
        string $path,
    ): ResponseInterface {
        $request = $this->requestFactory->createRequest($method, $this->apiUrl . $path);
        $request = $request->withHeader('Accept', 'application/json');

        return $this->httpClient->sendRequest($request);
    }

    /**
     * @throws \Exception If the response has the wrong status code or content type header.
     */
    private function ensureJsonResponse(
        ResponseInterface $response,
        int $expectedStatusCode,
    ): void {
        if ($response->getStatusCode() !== $expectedStatusCode) {
            throw new Exception('Server replied with status code ' . $response->getStatusCode());
        }

        if (! str_starts_with($response->getHeaderLine('content-type'), 'application/json')) {
            throw new Exception('Server replied not with JSON content.');
        }
    }

    /**
     * @throws \Exception If an error happens while processing the response.
     *
     * @return mixed[]
     */
    private function parseJsonResponseToArray(ResponseInterface $response): array
    {
        $responseBody = $response->getBody()->__toString();

        try {
            $data = json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $th) {
            throw new Exception('Response body contains no valid JSON: ' . $responseBody, 0, $th);
        }

        if (! is_array($data)) {
            throw new Exception('Response JSON does not contain an array: ' . $responseBody);
        }

        return $data;
    }
}
