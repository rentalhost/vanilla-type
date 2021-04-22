<?php

declare(strict_types = 1);

namespace Rentalhost\Vanilla\Type\Interfaces;

use Rentalhost\Vanilla\Type\Type;
use Rentalhost\Vanilla\Type\TypeArray;

interface ConstructorWithParentInterface
{
    /**
     * @param Type|TypeArray $classParent
     *
     * @return static
     */
    public static function constructWithParent(string $class, $classParent, array $attributesOrItems);
}
