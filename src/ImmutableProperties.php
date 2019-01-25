<?php

declare(strict_types=1);

namespace Codelicia\Immutable;

use ReflectionObject;
use ReflectionProperty;
use ReflectionType;
use RuntimeException;
use function array_key_exists;
use function class_implements;
use function get_class;
use function gettype;
use function in_array;
use function interface_exists;
use function is_object;
use function spl_object_hash;
use function sprintf;

trait ImmutableProperties
{
    /** @return string[][]|ReflectionProperty[][] */
    protected function &reflectionProperty(): array
    {
        static $reflectionProperties = [];

        if (! array_key_exists(spl_object_hash($this), $reflectionProperties)) {
            $reflectionProperties[spl_object_hash($this)] = [];
        }

        return $reflectionProperties[spl_object_hash($this)];
    }

    public function __construct()
    {
        $this->init();
    }

    public function init(): void
    {
        $reflection = new ReflectionObject($this);
        $store = &$this->reflectionProperty();
        $defaultProperties = $reflection->getDefaultProperties();

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            $propertyName = $reflectionProperty->name;
            /** @var \ReflectionType $type */
            $type = $reflectionProperty->getType();

            $store[$propertyName] = [
                'reflection' => $reflectionProperty,
                'value' => $defaultProperties[$propertyName] ?: null,
                'isInitialized' => isset($defaultProperties[$propertyName]) ? true : $reflectionProperty->isInitialized($this),
            ];

            if (isset($defaultProperties[$propertyName]) || $type->allowsNull()) {
                unset($this->{$propertyName});
            }
        }
    }

    public function __set(string $prop, $value)
    {
        $ref = &$this->reflectionProperty();
        $property = $ref[$prop];

        if ($property['isInitialized']) {
            // @TODO throws specific exception?
            throw new RuntimeException(sprintf('Cannot reassign value to property "%s"', $prop));
        }

        /** @var ReflectionProperty $reflection */
        $reflection = $property['reflection'];

        if ($reflection->hasType()) {
            /** @var ReflectionType $type */
            $type            = $reflection->getType();
            $expectedType    = $type->getName();
            $actualValueType = is_object($value) ? get_class($value) : gettype($value);

            if (is_object($value) && interface_exists($expectedType) && !in_array($expectedType, class_implements($actualValueType), false)) {
                throw TypeErrorExceptionFactory::fromWrongType($this, $prop, $expectedType, $actualValueType);
            }

            if (($expectedType !== $actualValueType && $type->isBuiltin())
                && (null !== $value && !$type->allowsNull())) {
                throw TypeErrorExceptionFactory::fromWrongType($this, $prop, $expectedType, $actualValueType);
            }
        }

        $ref[$prop] = [
            'reflection' => $property['reflection'],
            'value' => $value,
            'isInitialized' => true,
        ];
    }

    public function __get(string $prop)
    {
        return $this->{$prop} ?? $this->reflectionProperty()[$prop]['value'];
    }

    public function __debugInfo(): array
    {
        $debug = [];
        foreach ($this->reflectionProperty() as $property => $value) {
            $debug[$property] = $value['value'];
        }

        return $debug;
    }

    public function __isset(string $prop): bool
    {
        return array_key_exists($prop, $this->reflectionProperty());
    }

    // @TODO clean the reflection property static map on __destruct
}
