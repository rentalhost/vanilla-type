<?php

declare(strict_types = 1);

namespace Rentalhost\Vanilla\Type\Tests\Fixture\Traits;

use JetBrains\PhpStorm\ArrayShape;
use Rentalhost\Vanilla\Type\Tests\Fixture\Types\UserType;

trait UserTypeExampleTrait
{
    private static function getUserType(): UserType
    {
        return new UserType(self::getUserTypeAttributes());
    }

    #[ArrayShape([
        'nonType'          => 'bool',
        'nonTypeCallable'  => 'int',
        'basicString'      => 'string',
        'preferredColor'   => 'string[]',
        'preferredNumbers' => 'int[][]',
        'parentAccess'     => 'array',
        'parentAccesses'   => 'array',
    ])]
    private static function getUserTypeAttributes(): array
    {
        return [
            'nonType'          => true,
            'nonTypeCallable'  => 123,
            'basicString'      => 'hello',
            'preferredColor'   => [ 'name' => 'red' ],
            'preferredNumbers' => [
                [ 'number' => 1 ],
                [ 'number' => 2 ],
                [ 'number' => 3 ],
            ],
            'parentAccess'     => [],
            'parentAccesses'   => [],
        ];
    }
}
