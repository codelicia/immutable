<?php

declare(strict_types=1);

namespace Codelicia\Immutable;

use ReflectionType;
use function class_exists;
use function gettype;
use function interface_exists;
use function is_iterable;
use function is_object;

/**
 * @internal
 */
final class TypesMatchResolver
{
    public static function resolve($value, ReflectionType $reflectionType): bool
    {
        $valueType          = gettype($value);
        $reflectionTypeName = $reflectionType->getName();

        if ($value instanceof $reflectionTypeName
            && is_object($value)
            && ($reflectionTypeName === 'object' || interface_exists($reflectionTypeName) || class_exists($reflectionTypeName))) {
            return true;
        }

        $matchesTypes = self::normalizeTypeName($reflectionTypeName) === self::normalizeTypeName($valueType);

        return ($reflectionType->isBuiltin() && $matchesTypes)
            || self::isIterable($reflectionType, $value)
            || ($reflectionType->allowsNull() && $value === null);
    }

    private static function normalizeTypeName(string $type): string
    {
        return [
            'integer' => 'int',
            'double' => 'float',
            'boolean' => 'bool',
        ][$type] ?? $type;
    }

    private static function isIterable(ReflectionType $reflectionType, $value): bool
    {
        return $reflectionType->isBuiltin()
            && 'iterable' === $reflectionType->getName()
            && is_iterable($value);
    }
}
