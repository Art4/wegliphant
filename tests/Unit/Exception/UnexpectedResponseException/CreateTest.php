<?php

declare(strict_types=1);

namespace Tests\Art4\Wegliphant\Exception\UnexpectedResponseException;

use Art4\Wegliphant\Exception\UnexpectedResponseException;
use Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

#[CoversClass(UnexpectedResponseException::class)]
final class CreateTest extends TestCase
{
    public function testCreateWithoutPreviousReturnsUnexpectedResponseException(): void
    {
        $exception = UnexpectedResponseException::create(
            'error message',
            $this->createMock(ResponseInterface::class),
        );

        $this->assertInstanceOf(UnexpectedResponseException::class, $exception);
        $this->assertSame('error message', $exception->getMessage());
        $this->assertNull($exception->getPrevious());
    }

    public function testCreateWithPreviousReturnsUnexpectedResponseException(): void
    {
        $previous = new Exception();

        $exception = UnexpectedResponseException::create(
            'error message',
            $this->createMock(ResponseInterface::class),
            $previous,
        );

        $this->assertInstanceOf(UnexpectedResponseException::class, $exception);
        $this->assertSame('error message', $exception->getMessage());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
