<?php

declare(strict_types=1);

namespace Tests\Art4\Wegliphant\Client;

use Art4\Wegliphant\Client;
use Exception;
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

        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->exactly(1))->method('withHeader')->willReturn($request);

        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory->expects($this->exactly(1))->method('createRequest')->with('GET', 'https://www.weg.li/districts.json')->willReturn($request);

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

        $response = $client->listDistricts();

        $this->assertSame(
            $expected,
            $response,
        );
    }

    public function testListDistrictsThrowsClientException(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->exactly(1))->method('withHeader')->willReturn($request);

        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory->expects($this->exactly(1))->method('createRequest')->with('GET', 'https://www.weg.li/districts.json')->willReturn($request);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))->method('sendRequest')->willThrowException(
            $this->createMock(ClientExceptionInterface::class),
        );

        $client = Client::create(
            $httpClient,
            $requestFactory,
        );

        $this->expectException(ClientExceptionInterface::class);
        $this->expectExceptionMessage('');

        $client->listDistricts();
    }

    public function testListDistrictsExceptionOnWrongStatusCode(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->exactly(1))->method('withHeader')->willReturn($request);

        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory->expects($this->exactly(1))->method('createRequest')->with('GET', 'https://www.weg.li/districts.json')->willReturn($request);

        $response = $this->createConfiguredMock(
            ResponseInterface::class,
            [
                'getStatusCode' => 500,
            ]
        );

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))->method('sendRequest')->willReturn($response);

        $client = Client::create(
            $httpClient,
            $requestFactory,
        );

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Server replied with status code 500');

        $client->listDistricts();
    }

    public function testListDistrictsExceptionOnWrongContentTypeHeader(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->exactly(1))->method('withHeader')->willReturn($request);

        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory->expects($this->exactly(1))->method('createRequest')->with('GET', 'https://www.weg.li/districts.json')->willReturn($request);

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

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Server replied not with JSON content.');

        $client->listDistricts();
    }

    public function testListDistrictsExceptionOnInvalidJsonBody(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->exactly(1))->method('withHeader')->willReturn($request);

        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory->expects($this->exactly(1))->method('createRequest')->with('GET', 'https://www.weg.li/districts.json')->willReturn($request);

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

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Response body contains no valid JSON: invalid json');

        $client->listDistricts();
    }

    public function testListDistrictsExceptionOnJsonBodyWithoutArray(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->exactly(1))->method('withHeader')->willReturn($request);

        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory->expects($this->exactly(1))->method('createRequest')->with('GET', 'https://www.weg.li/districts.json')->willReturn($request);

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

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Response JSON does not contain an array: "this is not an array"');

        $client->listDistricts();
    }
}