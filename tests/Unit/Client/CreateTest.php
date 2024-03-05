<?php

declare(strict_types=1);

namespace Tests\Art4\Wegliphant\Client;

use Art4\Wegliphant\Client;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

#[CoversClass(Client::class)]
final class CreateTest extends TestCase
{
    public function testCreateReturnsClient(): void
    {
        $client = Client::create(
            $this->createMock(ClientInterface::class),
            $this->createMock(RequestFactoryInterface::class),
        );

        $this->assertInstanceOf(Client::class, $client);
    }
}
