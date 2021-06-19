<?php

declare(strict_types = 1);

namespace Rentalhost\Vanilla\Type\Tests\Fixture\Types;

use Rentalhost\Vanilla\Type\Type;

/**
 * @property string|null $string
 * @property string|null $stringNullable
 * @property int|null    $int
 * @property int|null    $intNullable
 * @property int|null    $integer
 * @property float|null  $float
 * @property float|null  $floatNullable
 * @property float|null  $double
 * @property bool|null   $bool
 * @property bool|null   $boolNullable
 * @property bool|null   $boolean
 * @property array|null  $array
 * @property array|null  $arrayNullable
 * @property object|null $object
 * @property object|null $objectNullable
 * @property null        $null
 * @property null        $nullNonNull
 */
class NativeType
    extends Type
{
    protected static ?array $casts = [
        'string'         => 'string',
        'stringNullable' => 'string',
        'int'            => 'int',
        'intNullable'    => 'int',
        'integer'        => 'integer',
        'float'          => 'float',
        'floatNullable'  => 'float',
        'double'         => 'double',
        'bool'           => 'bool',
        'boolNullable'   => 'bool',
        'boolean'        => 'boolean',
        'array'          => 'array',
        'arrayNullable'  => 'array',
        'object'         => 'object',
        'objectNullable' => 'object',
        'null'           => 'null',
        'nullNonNull'    => 'null',
    ];
}
