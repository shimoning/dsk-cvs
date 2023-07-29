<?php

namespace Shimoning\DskCvs\Entities;

use ArrayAccess;
use IteratorAggregate;
use Traversable;
use ArrayIterator;
use Shimoning\DskCvs\Contracts\Entities\Output;

class Collection implements ArrayAccess, IteratorAggregate, Output
{
    private array $_items = [];

    /**
     * コレクション
     *
     * @param mixed $items
     */
    public function __construct(mixed $items = [])
    {
        if (\is_array($items)) {
            $this->_items = $items;
        } else if ($items instanceof self) {
            $this->_items = $items->all();
        } else {
            $this->_items = (array)$items;
        }
    }

    public function all(): array
    {
        return $this->_items;
    }

    public function count(): int
    {
        return \count($this->_items);
    }

    public function offsetExists(mixed $offset): bool
    {
        return \array_key_exists($offset, $this->_items);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->_items[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (\is_null($offset)) {
            $this->_items[] = $value;
        } else {
            $this->_items[$offset] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->_items[$offset]);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->_items);
    }

    public function hasError(): bool
    {
        return false;
    }
}
