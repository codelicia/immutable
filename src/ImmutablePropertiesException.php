<?php

declare(strict_types=1);

namespace Codelicia\Immutable;

use function sprintf;

final class ImmutablePropertiesException extends ImmutableException
{
    public static function mutatingPropertiesAreNotAllowed(string $propertyName): self
    {
        return new self(sprintf('Cannot reassign value to property "%s"', $propertyName));
    }
}
