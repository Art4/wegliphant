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

        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->exactly(1))->method('withHeader')->willReturn($request);

        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory->expects($this->exactly(1))->method('createRequest')->with('GET', 'https://www.weg.li/charges.json')->willReturn($request);

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

        $response = $client->listCharges();

        $this->assertSame(
            $expected,
            $response,
        );
    }

    public function testListChargesThrowsClientException(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->exactly(1))->method('withHeader')->willReturn($request);

        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory->expects($this->exactly(1))->method('createRequest')->with('GET', 'https://www.weg.li/charges.json')->willReturn($request);

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

        $client->listCharges();
    }

    public function testListChargesExceptionOnWrongStatusCode(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->exactly(1))->method('withHeader')->willReturn($request);

        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory->expects($this->exactly(1))->method('createRequest')->with('GET', 'https://www.weg.li/charges.json')->willReturn($request);

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

        $client->listCharges();
    }

    public function testListChargesExceptionOnWrongContentTypeHeader(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->exactly(1))->method('withHeader')->willReturn($request);

        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory->expects($this->exactly(1))->method('createRequest')->with('GET', 'https://www.weg.li/charges.json')->willReturn($request);

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

        $client->listCharges();
    }

    public function testListChargesExceptionOnInvalidJsonBody(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->exactly(1))->method('withHeader')->willReturn($request);

        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory->expects($this->exactly(1))->method('createRequest')->with('GET', 'https://www.weg.li/charges.json')->willReturn($request);

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

        $client->listCharges();
    }

    public function testListChargesExceptionOnJsonBodyWithoutArray(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->exactly(1))->method('withHeader')->willReturn($request);

        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory->expects($this->exactly(1))->method('createRequest')->with('GET', 'https://www.weg.li/charges.json')->willReturn($request);

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

        $client->listCharges();
    }
}
