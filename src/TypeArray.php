<?php

declare(strict_types = 1);

namespace Rentalhost\Vanilla\Type;

use ArrayAccess;
use Countable;
use Iterator;
use JsonSerializable;

abstract class TypeArray
    implements ArrayAccess, Countable, JsonSerializable, Iterator
{
    public static string $castTo;

    protected array $items;

    protected array $itemsProcessed = [];

    public Type $parent;

    public function __construct(?array $items = null)
    {
        assert(is_a(static::$castTo, Type::class, true));

        $this->items = $items ?? [];
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function current()
    {
        return $this->offsetGet(key($this->items));
    }

    public function next(): void
    {
        next($this->items);
    }

    public function key(): string
    {
        return key($this->items);
    }

    public function valid(): bool
    {
        return key($this->items) !== null;
    }

    public function rewind(): void
    {
        reset($this->items);
    }

    /**
     * @param string|int $key
     */
    public function get($key, $default = null): ?Type
    {
        if ($this->offsetExists($key)) {
            return $this->offsetGet($key);
        }

        return $default;
    }

    public function items(): array
    {
        return $this->items;
    }

    public function jsonSerialize(): array
    {
        return $this->items();
    }

    public function offsetExists($key): bool
    {
        return array_key_exists($key, $this->items);
    }

    public function offsetGet($key)
    {
        if (!$this->offsetExists($key)) {
            return null;
        }

        if ($this->wasProcessed($key)) {
            return $this->itemsProcessed[$key];
        }

        /** @var Type $valueCasted */
        $valueCasted         = new static::$castTo($this->items[$key]);
        $valueCasted->parent = $this;

        return $this->itemsProcessed[$key] = $valueCasted;
    }

    public function offsetSet($key, $value): void
    {
        $this->items[$key] = $value;
    }

    public function offsetUnset($key): void
    {
        unset($this->items[$key]);
    }

    public function wasProcessed($key): bool
    {
        return array_key_exists($key, $this->itemsProcessed);
    }
}
