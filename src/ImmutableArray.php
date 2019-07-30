<?php

declare(strict_types=1);

namespace Codelicia\Immutable;

use ArrayAccess;

/**
 * @package Codelicia\Immutable
 */
class ImmutableArray implements ArrayAccess
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
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
}
