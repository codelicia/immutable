<?php

declare(strict_types=1);

namespace Codelicia\Immutable;

use TypeError;
use function get_class;
use function sprintf;

/**
 * @internal
 */
final class TypeErrorExceptionFactory
{
    private function __construct()
    {
    }

    public static function fromWrongType(object $context, string $propertyName, string $expectedType, string $actualValueType): TypeError
    {
        return new TypeError(sprintf(
            'Typed property %s::$%s must be %s, %s used',
            get_class($context),
            $propertyName,
            $expectedType,
            $actualValueType
        ));
    }
}
