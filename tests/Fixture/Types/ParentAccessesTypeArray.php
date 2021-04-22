<?php

declare(strict_types = 1);

namespace Rentalhost\Vanilla\Type\Tests\Fixture\Types;

use Rentalhost\Vanilla\Type\TypeArray;

/**
 * @property ParentAccessType $parent
 * @property string|null      $parentClass
 */
class ParentAccessesTypeArray
    extends TypeArray
{
    public static string $castTo = ParentAccessType::class;

    public function __construct(?array $items = null)
    {
        parent::__construct($items);

        $this->parentClass = get_class($this->parent);
    }
}
