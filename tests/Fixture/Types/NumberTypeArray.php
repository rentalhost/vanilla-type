<?php

declare(strict_types = 1);

namespace Rentalhost\Vanilla\Type\Tests\Fixture\Types;

use Rentalhost\Vanilla\Type\TypeArray;

class NumberTypeArray
    extends TypeArray
{
    public static string $castTo = NumberType::class;
}
