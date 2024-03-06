<?php

declare(strict_types=1);

namespace Tests\Art4\Wegliphant\Exception\UnexpectedResponseException;

use Art4\Wegliphant\Exception\UnexpectedResponseException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

#[CoversClass(UnexpectedResponseException::class)]
final class GetResponseTest extends TestCase
{
    public function testGetResponseReturnsResponse(): void
    {
        $response = $this->createMock(ResponseInterface::class);

        $exception = UnexpectedResponseException::create(
            'error message',
            $response,
        );

        $this->assertInstanceOf(UnexpectedResponseException::class, $exception);
        $this->assertSame('error message', $exception->getMessage());
        $this->assertSame($response, $exception->getResponse());
    }
}
