<?php

declare(strict_types=1);

namespace Tests\Art4\Wegliphant\Client;

use Art4\Wegliphant\Client;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Client::class)]
final class ListDistrictsTest extends TestCase
{
    public function testListDistrictsReturnsArray(): void
    {
        $client = Client::create();

        $response = $client->listDistricts();

        $this->assertIsArray($response);
    }
}
