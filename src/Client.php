<?php

declare(strict_types=1);

namespace Art4\Wegliphant;

use Exception;
use JsonException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

final class Client
{
    public static function create(
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
    ): self {
        return new self($httpClient, $requestFactory);
    }

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
        $request = $this->requestFactory->createRequest('GET', 'https://www.weg.li/districts.json');
        $request = $request->withHeader('Accept', 'application/json');

        $response = $this->httpClient->sendRequest($request);

        if ($response->getStatusCode() !== 200) {
            throw new Exception('Server replied with status code ' . $response->getStatusCode());
        }

        if (! str_starts_with($response->getHeaderLine('content-type'), 'application/json')) {
            throw new Exception('Server replied not with JSON content.');
        }

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
        $request = $this->requestFactory->createRequest('GET', 'https://www.weg.li/districts/' . $zip . '.json');
        $request = $request->withHeader('Accept', 'application/json');

        $response = $this->httpClient->sendRequest($request);

        if ($response->getStatusCode() !== 200) {
            throw new Exception('Server replied with status code ' . $response->getStatusCode());
        }

        if (! str_starts_with($response->getHeaderLine('content-type'), 'application/json')) {
            throw new Exception('Server replied not with JSON content.');
        }

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

    private function __construct(
        private ClientInterface $httpClient,
        private RequestFactoryInterface $requestFactory,
    ) {}
}
