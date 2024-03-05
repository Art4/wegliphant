<?php

declare(strict_types=1);

namespace Art4\Wegliphant;

final class Client
{
    public static function create(): self
    {
        return new self();
    }

    /**
     * @return mixed[]
     */
    public function listDistricts(): array
    {
        return [];
    }

    private function __construct() {}
}
