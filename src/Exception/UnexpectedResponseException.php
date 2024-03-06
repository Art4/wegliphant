<?php

declare(strict_types=1);

namespace Art4\Wegliphant\Exception;

use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Throwable;

final class UnexpectedResponseException extends RuntimeException
{
    public static function create(string $message, ResponseInterface $response, ?Throwable $previous = null): self
    {
        return new self($message, $response, $previous);
    }

    private ResponseInterface $response;

    /**
     * @internal Use ::create() instead
     */
    public function __construct(string $message, ResponseInterface $response, ?Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);

        $this->response = $response;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
