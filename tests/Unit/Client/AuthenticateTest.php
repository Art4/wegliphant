<?php

declare(strict_types=1);

namespace Tests\Art4\Wegliphant\Client;

use Art4\Wegliphant\Client;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

#[CoversClass(Client::class)]
final class AuthenticateTest extends TestCase
{
    public function testAuthenticateSetsCorrectHeader(): void
    {
        $expected = [];
        $apiKey = 'c3ab8ff13720e8ad9047dd39466b3c8974e592c2fa383d4a3960714caef0c4f2';

        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->exactly(2))->method('withHeader')->willReturnMap([
            ['Accept', 'application/json', $request],
            ['X-API-KEY', $apiKey, $request],
        ]);

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
        $client->authenticate($apiKey);

        $response = $client->listDistricts();
    }
}
