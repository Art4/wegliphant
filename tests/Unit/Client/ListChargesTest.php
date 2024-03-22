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
final class ListChargesTest extends TestCase
{
    public function testListChargesReturnsArray(): void
    {
        $expected = [
            [
                'tbnr' => '141312',
                'description' => 'Sie parkten im absoluten Haltverbot (Zeichen 283).',
                'fine' => '25.0',
                'bkat' => '§ 41 Abs. 1 iVm Anlage 2, § 49 StVO; § 24 Abs. 1, 3 Nr. 5 StVG; 52 BKat',
                'penalty' => null,
                'fap' => null,
                'points' => 0,
                'valid_from' => '2021-11-09T00:00:00.000+01:00',
                'valid_to' => null,
                'implementation' => null,
                'classification' => 5,
                'variant_table_id' => 741017,
                'rule_id' => 39,
                'table_id' => null,
                'required_refinements' => '00000000000000000000000000000000',
                'number_required_refinements' => 0,
                'max_fine' => '0.0',
                'created_at' => '2023-09-18T15:30:43.312+02:00',
                'updated_at' => '2023-09-18T15:30:43.312+02:00',
            ],
            [
                'tbnr' => '141313',
                'description' => 'Sie parkten im absoluten Haltverbot (Zeichen 283) und behinderten +) dadurch Andere.',
                'fine' => '40.0',
                'bkat' => '§ 41 Abs. 1 iVm Anlage 2, § 1 Abs. 2, § 49 StVO; § 24 Abs. 1, 3 Nr. 5 StVG; 52.1 BKat; § 19 OWiG',
                'penalty' => null,
                'fap' => null,
                'points' => 0,
                'valid_from' => '2021-11-09T00:00:00.000+01:00',
                'valid_to' => null,
                'implementation' => 2,
                'classification' => 5,
                'variant_table_id' => 741017,
                'rule_id' => 39,
                'table_id' => null,
                'required_refinements' => '00000000000000000000000000000000',
                'number_required_refinements' => 1,
                'max_fine' => '0.0',
                'created_at' => '2023-09-18T15:30:43.329+02:00',
                'updated_at' => '2023-09-18T15:30:43.329+02:00',
            ],
            [
                'tbnr' => '141314',
                'description' => 'Sie parkten länger als 1 Stunde im absoluten Haltverbot (Zeichen 283).',
                'fine' => '40.0',
                'bkat' => '§ 41 Abs. 1 iVm Anlage 2, § 49 StVO; § 24 Abs. 1, 3 Nr. 5 StVG; 52.2 BKat',
                'penalty' => null,
                'fap' => null,
                'points' => 0,
                'valid_from' => '2021-11-09T00:00:00.000+01:00',
                'valid_to' => null,
                'implementation' => null,
                'classification' => 5,
                'variant_table_id' => 741017,
                'rule_id' => 39,
                'table_id' => null,
                'required_refinements' => '00000000000000000000000000000000',
                'number_required_refinements' => 0,
                'max_fine' => '0.0',
                'created_at' => '2023-09-18T15:30:43.348+02:00',
                'updated_at' => '2023-09-18T15:30:43.348+02:00',
            ],
            [
                'tbnr' => '141315',
                'description' => 'Sie parkten länger als 1 Stunde im absoluten Haltverbot (Zeichen 283) und behinderten +) dadurch Andere.',
                'fine' => '50.0',
                'bkat' => '§ 41 Abs. 1 iVm Anlage 2, § 1 Abs. 2, § 49 StVO; § 24 Abs. 1, 3 Nr. 5 StVG; 52.2.1 BKat; § 19 OWiG',
                'penalty' => null,
                'fap' => null,
                'points' => 0,
                'valid_from' => '2021-11-09T00:00:00.000+01:00',
                'valid_to' => null,
                'implementation' => 2,
                'classification' => 5,
                'variant_table_id' => 741017,
                'rule_id' => 39,
                'table_id' => null,
                'required_refinements' => '00000000000000000000000000000000',
                'number_required_refinements' => 1,
                'max_fine' => '0.0',
                'created_at' => '2023-09-18T15:30:43.369+02:00',
                'updated_at' => '2023-09-18T15:30:43.369+02:00',
            ],
        ];

        $apiKey = 'c3ab8ff13720e8ad9047dd39466b3c8974e592c2fa383d4a3960714caef0c4f2';

        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->exactly(2))->method('withHeader')->willReturnMap([
            ['Accept', 'application/json', $request],
            ['X-API-KEY', $apiKey, $request],
        ]);

        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory->expects($this->exactly(1))->method('createRequest')->with('GET', 'https://www.weg.li/api/charges')->willReturn($request);

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

        $response = $client->listCharges();

        $this->assertSame(
            $expected,
            $response,
        );
    }

    public function testListChargesThrowsClientException(): void
    {
        $apiKey = 'c3ab8ff13720e8ad9047dd39466b3c8974e592c2fa383d4a3960714caef0c4f2';

        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->exactly(2))->method('withHeader')->willReturnMap([
            ['Accept', 'application/json', $request],
            ['X-API-KEY', $apiKey, $request],
        ]);

        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory->expects($this->exactly(1))->method('createRequest')->with('GET', 'https://www.weg.li/api/charges')->willReturn($request);

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

        $client->listCharges();
    }

    public function testListChargesThrowsUnexpectedResponseExceptionOnWrongStatusCode(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->exactly(1))->method('withHeader')->willReturn($request);

        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory->expects($this->exactly(1))->method('createRequest')->with('GET', 'https://www.weg.li/api/charges')->willReturn($request);

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

        $client->listCharges();
    }

    public function testListChargesThrowsUnexpectedResponseExceptionOnWrongContentTypeHeader(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->exactly(1))->method('withHeader')->willReturn($request);

        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory->expects($this->exactly(1))->method('createRequest')->with('GET', 'https://www.weg.li/api/charges')->willReturn($request);

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

        $client->listCharges();
    }

    public function testListChargesThrowsUnexpectedResponseExceptionOnInvalidJsonBody(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->exactly(1))->method('withHeader')->willReturn($request);

        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory->expects($this->exactly(1))->method('createRequest')->with('GET', 'https://www.weg.li/api/charges')->willReturn($request);

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

        $client->listCharges();
    }

    public function testListChargesThrowsUnexpectedResponseExceptionOnJsonBodyWithoutArray(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->exactly(1))->method('withHeader')->willReturn($request);

        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory->expects($this->exactly(1))->method('createRequest')->with('GET', 'https://www.weg.li/api/charges')->willReturn($request);

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

        $client->listCharges();
    }
}
