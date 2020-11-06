<?php

declare(strict_types = 1);

namespace Rentalhost\Vanilla\Type\Tests;

use PHPUnit\Framework\TestCase;
use Rentalhost\Vanilla\Type\Tests\Fixture\Traits\UserTypeExampleTrait;
use Rentalhost\Vanilla\Type\Tests\Fixture\Types\ColorType;
use Rentalhost\Vanilla\Type\Tests\Fixture\Types\NonType;
use Rentalhost\Vanilla\Type\TypeArray;

class TypeTest
    extends TestCase
{
    use UserTypeExampleTrait;

    public function testArrayAccess(): void
    {
        $type = self::getUserType();

        static::assertSame('hello', $type['basicString']);

        $type['basicString'] = 'modified';
        static::assertSame('modified', $type->basicString);
    }

    public function testCall(): void
    {
        $type = self::getUserType();

        $type->basicBoolean();
        static::assertTrue($type->basicBoolean);

        $type->basicBoolean(false);
        static::assertFalse($type->basicBoolean);
    }

    public function testGetAttributes(): void
    {
        $type = self::getUserType();

        static::assertSame(self::getUserTypeAttributes(), $type->getAttributes());

        // Synonyms.
        static::assertSame(self::getUserTypeAttributes(), $type->toArray());
        static::assertSame(self::getUserTypeAttributes(), $type->jsonSerialize());
    }

    public function testGetAttributesAfterProcessing(): void
    {
        $type = self::getUserType();

        static::assertInstanceOf(ColorType::class, $type->preferredColor);

        static::assertSame(self::getUserTypeAttributes(), $type->getAttributes());
    }

    public function testGetDefault(): void
    {
        $type = self::getUserType();

        static::assertNull($type->undefinedKey);
        static::assertSame('default', $type->get('undefinedKey', 'default'));
    }

    public function testGetFromProcessedCache(): void
    {
        $type = self::getUserType();

        static::assertInstanceOf(ColorType::class, $type->preferredColor); // process and stores to cache
        static::assertInstanceOf(ColorType::class, $type->preferredColor); // from cache directly
    }

    public function testGetTypeArray(): void
    {
        $type = self::getUserType();

        static::assertInstanceOf(TypeArray::class, $type->preferredNumbers);
    }

    public function testIssetUnset(): void
    {
        $type = self::getUserType();

        static::assertTrue(isset($type->basicString));

        unset($type->basicString);
        static::assertFalse(isset($type->basicString));
    }

    public function testObjectAccess(): void
    {
        $type = self::getUserType();

        static::assertSame('hello', $type->basicString);

        $type->basicString = 'modified1';
        static::assertSame('modified1', $type->basicString);
    }

    public function testParentAccess(): void
    {
        $type = self::getUserType();

        static::assertNull($type->parent);
        static::assertSame($type, $type->preferredColor->parent);
    }

    public function testParentNonType(): void
    {
        $type = self::getUserType();

        static::assertInstanceOf(NonType::class, $type->nonType);

        // NonType will have not access to parent instance becaus it is a non Type, in fact.
        static::assertFalse(property_exists($type->nonType, 'parent'));
    }

    public function testToJson(): void
    {
        $type = self::getUserType();

        static::assertSame(
            json_encode(self::getUserTypeAttributes(), JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT),
            json_encode($type, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT)
        );
    }
}
