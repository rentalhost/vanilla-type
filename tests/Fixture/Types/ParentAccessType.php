<?php

declare(strict_types = 1);

namespace Rentalhost\Vanilla\Type\Tests\Fixture\Types;

use Rentalhost\Vanilla\Type\Type;

/**
 * @property UserType    $parent
 * @property string|null $parentClass
 */
class ParentAccessType
    extends Type
{
    public function __construct(?array $attributes = null)
    {
        parent::__construct($attributes);

        $this->parentClass = get_class($this->parent);
    }
}
