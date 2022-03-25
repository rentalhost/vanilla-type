<?php

declare(strict_types = 1);

namespace Rentalhost\Vanilla\Type;

use ArrayAccess;
use Countable;
use Iterator;
use JsonSerializable;
use Rentalhost\Vanilla\Type\Interfaces\ConstructorWithParentInterface;
use Rentalhost\Vanilla\Type\Traits\ConstructorWithParentTrait;

abstract class TypeArray
    implements ArrayAccess, Countable, JsonSerializable, Iterator, ConstructorWithParentInterface
{
    use ConstructorWithParentTrait;

    public static string $castTo;

    protected array $items;

    protected array $itemsProcessed = [];

    public Type $parent;

    public function __construct(array|null $items = null)
    {
        assert(is_a(static::$castTo, Type::class, true));

        $this->items = $items ?? [];
    }

    private function syncItems(): void
    {
        foreach ($this->itemsProcessed as $itemKey => $item) {
            if ($item instanceof Type) {
                $this->items[$itemKey] = $item->toArray();
            }
        }
    }

    public function copy(): self
    {
        return new static($this->toArray());
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function current(): mixed
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

    public function get(string|int $key, $default = null): Type|null
    {
        if ($this->offsetExists($key)) {
            return $this->offsetGet($key);
        }

        return $default;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->items);
    }

    public function offsetGet(mixed $offset): mixed
    {
        if (!$this->offsetExists($offset)) {
            return null;
        }

        if ($this->wasProcessed($offset)) {
            return $this->itemsProcessed[$offset];
        }

        if (!is_array($this->items[$offset])) {
            return null;
        }

        /** @var Type $castTo */
        $castTo = static::$castTo;

        return $this->itemsProcessed[$offset] = $castTo::constructWithParent(static::$castTo, $this, $this->items[$offset]);
    }

    public function offsetSet($offset, $value): void
    {
        $this->items[$offset] = $value;

        unset($this->itemsProcessed[$offset]);
    }

    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    public function toArray(): array
    {
        $this->syncItems();

        return $this->items;
    }

    public function wasProcessed($key): bool
    {
        return array_key_exists($key, $this->itemsProcessed);
    }
}
