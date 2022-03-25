<?php

declare(strict_types = 1);

namespace Rentalhost\Vanilla\Type\Traits;

use ReflectionClass;
use Rentalhost\Vanilla\Type\Type;
use Rentalhost\Vanilla\Type\TypeArray;

trait ConstructorWithParentTrait
{
    public static function constructWithParent(string $class, Type|TypeArray $classParent, array $attributesOrItems)
    {
        /** @var Type|TypeArray $classInstance */
        $classInstance         = (new ReflectionClass($class))->newInstanceWithoutConstructor();
        $classInstance->parent = $classParent;
        $classInstance->__construct($attributesOrItems);

        return $classInstance;
    }
}
