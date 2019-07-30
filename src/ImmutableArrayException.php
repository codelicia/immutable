<?php

declare(strict_types=1);

namespace Codelicia\Immutable;

final class ImmutableArrayException extends ImmutableException
{
    public static function mutatingArrayIsNotAllowed(): self
    {
        return new self('Cannot change an immutable array');
    }
}
