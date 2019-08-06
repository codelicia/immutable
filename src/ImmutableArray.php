<?php

declare(strict_types=1);

namespace Codelicia\Immutable;

use ArrayAccess;
use Iterator;

/**
 * @package Codelicia\Immutable
 */
class ImmutableArray implements ArrayAccess, Iterator
{
    private array $data;
    private int $position;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->position = 0;
    }

    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetSet($offset, $value): void
    {
        throw ImmutableArrayException::mutatingArrayIsNotAllowed();
    }

    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    public function offsetUnset($offset): bool
    {
        throw ImmutableArrayException::mutatingArrayIsNotAllowed();
    }

    public function rewind() {
        $this->position = 0;
    }

    public function current() {
        return $this->data[$this->position];
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    public function valid() {
        return isset($this->data[$this->position]);
    }
}
