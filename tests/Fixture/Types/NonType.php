<?php

declare(strict_types = 1);

namespace Rentalhost\Vanilla\Type\Tests\Fixture\Types;

class NonType
{
    /** @noinspection MagicMethodsValidityInspection */
    public function __set($name, $value)
    {
        throw new \RuntimeException('NonType not supports __set()');
    }
}
