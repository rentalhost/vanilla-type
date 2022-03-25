<?php

declare(strict_types = 1);

namespace Rentalhost\Vanilla\Type;

use ArrayAccess;
use JsonSerializable;
use Rentalhost\Vanilla\Type\Interfaces\ConstructorWithParentInterface;
use Rentalhost\Vanilla\Type\Traits\ConstructorWithParentTrait;

abstract class Type
    implements ArrayAccess, JsonSerializable, ConstructorWithParentInterface
{
    use ConstructorWithParentTrait;

    private const NATIVE_TYPES = [ 'string', 'int', 'integer', 'float', 'double', 'boolean', 'bool', 'array', 'object', 'null' ];

    /** @var string[]|null */
    protected static array|null $arrayCasts = null;

    /** @var string[]|null */
    protected static array|null $casts = null;

    /** @var array */
    protected array $attributes = [];

    /** @var array|object[] */
    protected array $attributesProcessed = [];

    public Type|TypeArray|null $parent = null;

    public function __construct(array|null $attributes = null)
    {
        if ($attributes) {
            foreach ($attributes as $key => $value) {
                $this->attributes[$key] = $value;
            }
        }
    }

    private static function processNativeType(string $castClass, $castValue)
    {
        if ($castValue === null) {
            return null;
        }

        return match ($castClass) {
            'string'          => (string) $castValue,
            'int', 'integer'  => (int) $castValue,
            'float', 'double' => (float) $castValue,
            'bool', 'boolean' => (bool) $castValue,
            'array'           => (array) $castValue,
            'object'          => (object) $castValue,
            default           => null,
        };
    }

    private function processType(string $key, array $usingCastsArray)
    {
        $castClass = $usingCastsArray[$key];
        $castValue = $this->attributes[$key];

        if (in_array($castClass, self::NATIVE_TYPES, true)) {
            return $this->attributesProcessed[$key] = self::processNativeType($castClass, $castValue);
        }

        if (is_callable($castClass)) {
            return $this->attributesProcessed[$key] = $castClass($castValue);
        }

        return $this->attributesProcessed[$key] =
            is_a($castClass, ConstructorWithParentInterface::class, true)
                ? self::constructWithParent($castClass, $this, $castValue)
                : new $castClass($castValue);
    }

    private function syncAttributes(): void
    {
        foreach ($this->attributesProcessed as $attributeKey => $attribute) {
            if ($attribute instanceof self ||
                $attribute instanceof TypeArray) {
                $this->attributes[$attributeKey] = $attribute->toArray();
            }
        }
    }

    public function __call(string $method, array $parameters): self
    {
        $this->attributes[$method] = count($parameters) > 0 ? $parameters[0] : true;

        return $this;
    }

    public function __get(string $key): mixed
    {
        return $this->get($key);
    }

    public function __set(string $key, mixed $value): void
    {
        $this->offsetSet($key, $value);
    }

    public function __isset(string $key): bool
    {
        return $this->offsetExists($key);
    }

    public function __unset(string $key): void
    {
        $this->offsetUnset($key);
    }

    public function copy(): self
    {
        return new static($this->toArray());
    }

    public function get(string $key, $default = null): mixed
    {
        if (!$this->offsetExists($key)) {
            return $default;
        }

        if ($this->wasProcessed($key)) {
            return $this->attributesProcessed[$key];
        }

        if (isset(static::$casts[$key])) {
            return $this->processType($key, static::$casts);
        }

        if (isset(static::$arrayCasts[$key])) {
            return $this->processType($key, static::$arrayCasts);
        }

        return $this->attributes[$key];
    }

    /** @return array[] */
    public function getAttributes(): array
    {
        return $this->toArray();
    }

    /** @return array[] */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /** @param string $offset */
    public function offsetExists($offset): bool
    {
        return isset($this->attributes[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    public function offsetSet(mixed $offset, $value): void
    {
        $this->attributes[$offset] = $value;

        unset($this->attributesProcessed[$offset]);
    }

    /** @param string $offset */
    public function offsetUnset($offset): void
    {
        unset($this->attributes[$offset]);
    }

    /** @return array[] */
    public function toArray(): array
    {
        $this->syncAttributes();

        return $this->attributes;
    }

    public function toJson(int|null $options = null): string
    {
        return json_encode($this->jsonSerialize(), ($options ?? 0) | JSON_THROW_ON_ERROR);
    }

    public function wasProcessed(string $key): bool
    {
        return array_key_exists($key, $this->attributesProcessed);
    }
}
