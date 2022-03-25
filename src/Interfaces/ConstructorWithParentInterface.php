<?php

declare(strict_types = 1);

namespace Rentalhost\Vanilla\Type\Interfaces;

use Rentalhost\Vanilla\Type\Type;
use Rentalhost\Vanilla\Type\TypeArray;

interface ConstructorWithParentInterface
{
    /** @return static */
    public static function constructWithParent(string $class, Type|TypeArray $classParent, array $attributesOrItems);
}
