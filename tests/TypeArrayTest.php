<?php

declare(strict_types = 1);

namespace Rentalhost\Vanilla\Type\Tests;

use PHPUnit\Framework\TestCase;
use Rentalhost\Vanilla\Type\Tests\Fixture\Traits\UserTypeExampleTrait;
use Rentalhost\Vanilla\Type\Tests\Fixture\Types\NumberType;

class TypeArrayTest
    extends TestCase
{
    use UserTypeExampleTrait;

    public function testCount(): void
    {
        $type = self::getUserType();

        static::assertCount(3, $type->preferredNumbers);
        static::assertCount(3, $type->preferredNumbers->items());
        static::assertSame(3, $type->preferredNumbers->count());
    }

    public function testGetDefault(): void
    {
        $type = self::getUserType();

        static::assertSame(1, $type->preferredNumbers->get(0)->number);
        static::assertSame(1, $type->preferredNumbers->get('0')->number);

        static::assertSame(4, $type->preferredNumbers->get(3, new NumberType([ 'number' => 4 ]))->number);
    }

    public function testGetUndefined(): void
    {
        $type = self::getUserType();

        static::assertNull($type->preferredNumbers->get(4));
        static::assertNull($type->preferredNumbers[4]);
    }

    public function testIssetUnset(): void
    {
        $type = self::getUserType();

        static::assertTrue(isset($type->preferredNumbers[0]));

        unset($type->preferredNumbers[0]);
        static::assertFalse(isset($type->preferredNumbers[0]));
    }

    public function testOffsetExists(): void
    {
        $type = self::getUserType();

        static::assertTrue($type->preferredNumbers->offsetExists(0));
    }

    public function testOffsetGet(): void
    {
        $type = self::getUserType();

        static::assertInstanceOf(NumberType::class, $type->preferredNumbers[0]);
    }

    public function testOffsetGetSet(): void
    {
        $type = self::getUserType();

        static::assertInstanceOf(NumberType::class, $type->preferredNumbers[0]);

        $type->preferredNumbers[3] = [ 'number' => 4 ];
        static::assertInstanceOf(NumberType::class, $type->preferredNumbers[3]);
        static::assertSame(4, $type->preferredNumbers[3]->number);
    }

    public function testParentAccess(): void
    {
        $type = self::getUserType();

        static::assertSame($type, $type->preferredNumbers->parent);
        static::assertSame($type->preferredNumbers, $type->preferredNumbers[0]->parent);
        static::assertSame($type, $type->preferredNumbers[0]->parent->parent);
    }
}
