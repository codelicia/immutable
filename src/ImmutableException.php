<?php

declare(strict_types=1);

namespace Codelicia\Immutable;

use RuntimeException;
use function sprintf;

final class ImmutableException extends RuntimeException
{
    public static function mutatingPropertiesAreNotAllowed(string $propertyName): self
    {
        return new self(sprintf('Cannot reassign value to property "%s"', $propertyName));
    }
}
