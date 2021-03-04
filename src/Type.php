<?php

declare(strict_types = 1);

namespace Rentalhost\Vanilla\Type;

use ArrayAccess;
use JsonSerializable;

abstract class Type
    implements ArrayAccess, JsonSerializable
{
    /** @var string[]|null */
    protected static ?array $arrayCasts = null;

    /** @var string[]|null */
    protected static ?array $casts = null;

    /** @var array|mixed[] */
    protected array $attributes = [];

    /** @var array|object[] */
    protected array $attributesProcessed = [];

    /** @var Type|TypeArray|null */
    public $parent;

    public function __construct(?array $attributes = null)
    {
        if ($attributes) {
            foreach ($attributes as $key => $value) {
                $this->attributes[$key] = $value;
            }
        }
    }

    private function processType(string $key, array $usingCastsArray)
    {
        $value = $this->attributes[$key];

        $processedValue = new $usingCastsArray[$key]($value);

        if ($processedValue instanceof self ||
            $processedValue instanceof TypeArray) {
            $processedValue->parent = $this;
        }

        return $this->attributesProcessed[$key] = $processedValue;
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

    /** @return mixed */
    public function __get(string $key)
    {
        return $this->get($key);
    }

    /** @param mixed $value */
    public function __set(string $key, $value): void
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

    /** @return mixed */
    public function get(string $key, $default = null)
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

    /** @return array|mixed[] */
    public function getAttributes(): array
    {
        return $this->toArray();
    }

    /** @return array|mixed[] */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /** @param string $offset */
    public function offsetExists($offset): bool
    {
        return isset($this->attributes[$offset]);
    }

    /**
     * @param string $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param string $offset
     * @param mixed  $offset
     */
    public function offsetSet($offset, $value): void
    {
        $this->attributes[$offset] = $value;

        unset($this->attributesProcessed[$offset]);
    }

    /** @param string $offset */
    public function offsetUnset($offset): void
    {
        unset($this->attributes[$offset]);
    }

    /** @return array|mixed[] */
    public function toArray(): array
    {
        $this->syncAttributes();

        return $this->attributes;
    }

    public function toJson(?int $options = null): string
    {
        return json_encode($this->jsonSerialize(), ($options ?? 0) | JSON_THROW_ON_ERROR);
    }

    public function wasProcessed(string $key): bool
    {
        return array_key_exists($key, $this->attributesProcessed);
    }
}
