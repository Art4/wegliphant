<?php

declare(strict_types=1);

namespace Tests\Art4\Wegliphant\Client;

use Art4\Wegliphant\Client;
use Art4\Wegliphant\Exception\UnexpectedResponseException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

#[CoversClass(Client::class)]
final class ListDistrictsTest extends TestCase
{
    public function testListDistrictsReturnsArray(): void
    {
        $expected = [
            [
                'name' => 'Berlin',
                'zip' => '12305',
                'email' => 'mail@example.com',
                'prefixes' => [
                    'B',
                ],
                'latitude' => 52.5170365,
                'longitude' => 13.3888599,
                'aliases' => null,
                'personal_email' => false,
                'created_at' => '2019-09-24T14:56:35.624+02:00',
                'updated_at' => '2020-03-06T17:53:09.034+01:00',
            ],
            [
                'name' => 'Berlin',
                'zip' => '12347',
                'email' => 'mail@example.com',
                'prefixes' => [
                    'B',
                ],
                'latitude' => 52.5170365,
                'longitude' => 13.3888599,
                'aliases' => null,
                'personal_email' => false,
                'created_at' => '2019-09-24T14:56:35.624+02:00',
                'updated_at' => '2020-03-06T17:53:10.027+01:00',
            ],
            [
                'name' => 'Berlin',
                'zip' => '12349',
                'email' => 'mail@example.com',
                'prefixes' => [
                    'B',
                ],
                'latitude' => 52.5170365,
                'longitude' => 13.3888599,
                'aliases' => null,
                'personal_email' => false,
                'created_at' => '2019-09-24T14:56:35.624+02:00',
                'updated_at' => '2020-03-06T17:53:10.365+01:00',
            ],
        ];

        $apiKey = 'c3ab8ff13720e8ad9047dd39466b3c8974e592c2fa383d4a3960714caef0c4f2';

        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->exactly(2))->method('withHeader')->willReturnMap([
            ['Accept', 'application/json', $request],
            ['X-API-KEY', $apiKey, $request],
        ]);

        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory->expects($this->exactly(1))->method('createRequest')->with('GET', 'https://www.weg.li/api/districts')->willReturn($request);

        $stream = $this->createConfiguredMock(
            StreamInterface::class,
            [
                '__toString' => json_encode($expected),
            ],
        );

        $response = $this->createConfiguredMock(
            ResponseInterface::class,
            [
                'getStatusCode' => 200,
                'getHeaderLine' => 'application/json',
                'getBody' => $stream,
            ]
        );

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))->method('sendRequest')->willReturn($response);

        $client = Client::create(
            $httpClient,
            $requestFactory,
        );
        $client->authenticate($apiKey);

        $response = $client->listDistricts();

        $this->assertSame(
            $expected,
            $response,
        );
    }

    public function testListDistrictsThrowsClientException(): void
    {
        $apiKey = 'c3ab8ff13720e8ad9047dd39466b3c8974e592c2fa383d4a3960714caef0c4f2';

        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->exactly(2))->method('withHeader')->willReturnMap([
            ['Accept', 'application/json', $request],
            ['X-API-KEY', $apiKey, $request],
        ]);

        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory->expects($this->exactly(1))->method('createRequest')->with('GET', 'https://www.weg.li/api/districts')->willReturn($request);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))->method('sendRequest')->willThrowException(
            $this->createMock(ClientExceptionInterface::class),
        );

        $client = Client::create(
            $httpClient,
            $requestFactory,
        );
        $client->authenticate($apiKey);

        $this->expectException(ClientExceptionInterface::class);
        $this->expectExceptionMessage('');

        $client->listDistricts();
    }

    public function testListDistrictsThrowsUnexpectedResponseExceptionOnWrongStatusCode(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->exactly(1))->method('withHeader')->willReturn($request);

        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory->expects($this->exactly(1))->method('createRequest')->with('GET', 'https://www.weg.li/api/districts')->willReturn($request);

        $response = $this->createConfiguredMock(
            ResponseInterface::class,
            [
                'getStatusCode' => 401,
            ]
        );

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))->method('sendRequest')->willReturn($response);

        $client = Client::create(
            $httpClient,
            $requestFactory,
        );

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage('Server replied with the status code 401, but 200 was expected.');

        $client->listDistricts();
    }

    public function testListDistrictsThrowsUnexpectedResponseExceptionOnWrongContentTypeHeader(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->exactly(1))->method('withHeader')->willReturn($request);

        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory->expects($this->exactly(1))->method('createRequest')->with('GET', 'https://www.weg.li/api/districts')->willReturn($request);

        $response = $this->createConfiguredMock(
            ResponseInterface::class,
            [
                'getStatusCode' => 200,
                'getHeaderLine' => 'text/html',
            ]
        );

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))->method('sendRequest')->willReturn($response);

        $client = Client::create(
            $httpClient,
            $requestFactory,
        );

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage('Server replied not with JSON content.');

        $client->listDistricts();
    }

    public function testListDistrictsThrowsUnexpectedResponseExceptionOnInvalidJsonBody(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->exactly(1))->method('withHeader')->willReturn($request);

        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory->expects($this->exactly(1))->method('createRequest')->with('GET', 'https://www.weg.li/api/districts')->willReturn($request);

        $stream = $this->createConfiguredMock(
            StreamInterface::class,
            [
                '__toString' => 'invalid json',
            ],
        );

        $response = $this->createConfiguredMock(
            ResponseInterface::class,
            [
                'getStatusCode' => 200,
                'getHeaderLine' => 'application/json',
                'getBody' => $stream,
            ]
        );

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))->method('sendRequest')->willReturn($response);

        $client = Client::create(
            $httpClient,
            $requestFactory,
        );

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage('Response body contains no valid JSON: invalid json');

        $client->listDistricts();
    }

    public function testListDistrictsThrowsUnexpectedResponseExceptionOnJsonBodyWithoutArray(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->exactly(1))->method('withHeader')->willReturn($request);

        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory->expects($this->exactly(1))->method('createRequest')->with('GET', 'https://www.weg.li/api/districts')->willReturn($request);

        $stream = $this->createConfiguredMock(
            StreamInterface::class,
            [
                '__toString' => '"this is not an array"',
            ],
        );

        $response = $this->createConfiguredMock(
            ResponseInterface::class,
            [
                'getStatusCode' => 200,
                'getHeaderLine' => 'application/json',
                'getBody' => $stream,
            ]
        );

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))->method('sendRequest')->willReturn($response);

        $client = Client::create(
            $httpClient,
            $requestFactory,
        );

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage('Response JSON does not contain an array: "this is not an array"');

        $client->listDistricts();
    }
}
