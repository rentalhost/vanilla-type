<?php

declare(strict_types = 1);

namespace Rentalhost\Vanilla\Type\Tests\Fixture\Types;

use Rentalhost\Vanilla\Type\Type;
use Rentalhost\Vanilla\Type\TypeArray;

/**
 * @property string|null             $basicString
 * @property bool|null               $basicBoolean
 *
 * @property ColorType|null          $preferredColor
 * @property TypeArray|NumberType[]  $preferredNumbers
 *
 * @property null                    $undefinedKey
 *
 * @property NonType                 $nonType
 *
 * @property ParentAccessType        $parentAccess
 * @property ParentAccessesTypeArray $parentAccesses
 *
 * @method self basicBoolean(bool $basicBoolean = true)
 */
class UserType
    extends Type
{
    protected static ?array $arrayCasts = [
        'preferredNumbers' => NumberTypeArray::class,
        'parentAccesses'   => ParentAccessesTypeArray::class,
    ];

    protected static ?array $casts = [
        'preferredColor' => ColorType::class,
        'nonType'        => NonType::class,
        'parentAccess'   => ParentAccessType::class,
    ];
}
