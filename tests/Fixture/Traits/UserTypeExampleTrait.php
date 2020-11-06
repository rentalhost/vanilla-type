<?php

declare(strict_types = 1);

namespace Rentalhost\Vanilla\Type\Tests\Fixture\Traits;

use Rentalhost\Vanilla\Type\Tests\Fixture\Types\UserType;

trait UserTypeExampleTrait
{
    private static function getUserType(): UserType
    {
        return new UserType(self::getUserTypeAttributes());
    }

    private static function getUserTypeAttributes(): array
    {
        return [
            'nonType'          => true,
            'basicString'      => 'hello',
            'preferredColor'   => [ 'name' => 'red' ],
            'preferredNumbers' => [
                [ 'number' => 1 ],
                [ 'number' => 2 ],
                [ 'number' => 3 ],
            ]
        ];
    }
}
