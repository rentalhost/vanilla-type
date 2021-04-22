<?php

declare(strict_types = 1);

namespace Rentalhost\Vanilla\Type\Tests;

use PHPUnit\Framework\TestCase;
use Rentalhost\Vanilla\Type\Tests\Fixture\Traits\UserTypeExampleTrait;
use Rentalhost\Vanilla\Type\Tests\Fixture\Types\NumberType;
use Rentalhost\Vanilla\Type\Tests\Fixture\Types\UserType;

class TypeArrayTest
    extends TestCase
{
    use UserTypeExampleTrait;

    public function testCopy(): void
    {
        $preferredNumbers            = self::getUserType()->preferredNumbers;
        $preferredNumbers[0]->number = 999;

        $preferredNumbersCopy            = $preferredNumbers->copy();
        $preferredNumbersCopy[0]->number = 888;

        static::assertSame(999, $preferredNumbers[0]->number);
        static::assertSame(888, $preferredNumbersCopy[0]->number);
    }

    public function testCount(): void
    {
        $type = self::getUserType();

        static::assertCount(3, $type->preferredNumbers);
        static::assertCount(3, $type->preferredNumbers->toArray());
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

    public function testIterator(): void
    {
        foreach (self::getUserType()->preferredNumbers as $preferredNumber) {
            static::assertIsInt($preferredNumber->number);
        }
    }

    public function testJsonSerializable(): void
    {
        $type = self::getUserType();

        static::assertSame(
            json_encode(self::getUserTypeAttributes()['preferredNumbers'], JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT),
            json_encode($type->preferredNumbers, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT)
        );
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

    public function testParentAccessConstructor(): void
    {
        $type = self::getUserType();

        static::assertSame(UserType::class, $type->parentAccesses->parentClass, 'parent instance must be available at constructor');
    }

    public function testSetWithReprocessing(): void
    {
        $type = self::getUserType();

        static::assertSame(1, $type->preferredNumbers[0]->number);

        $type->preferredNumbers[0] = [ 'number' => 2 ];

        static::assertSame(2, $type->preferredNumbers[0]->number);
    }

    public function testToArrayAfterNestedSet(): void
    {
        $type                              = self::getUserType();
        $type->preferredNumbers[0]->number = 4;

        static::assertSame(4, $type->preferredNumbers[0]->number);

        static::assertNotSame(self::getUserType()->toArray(), $type->toArray());
        static::assertNotSame(self::getUserType()->toJson(), $type->toJson());
    }

    public function testUnsetWithReprocessing(): void
    {
        $type = self::getUserType();

        static::assertSame(1, $type->preferredNumbers[0]->number);

        unset($type->preferredNumbers[0]);

        static::assertNull($type->preferredNumbers[0]);
    }
}
