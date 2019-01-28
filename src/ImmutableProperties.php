<?php

declare(strict_types=1);

namespace Codelicia\Immutable;

use ReflectionObject;
use ReflectionProperty;
use ReflectionType;
use function array_key_exists;
use function class_implements;
use function get_class;
use function gettype;
use function in_array;
use function interface_exists;
use function is_object;
use function spl_object_hash;

/**
 * It provides manages and check state of properties to make sure it
 * doesn't change. The properties are store under a static variable
 * `$reflectionProperties` inside `self#reflectionProperty()` where
 * reference to objects are store along with the necessary data to
 * make does checks.
 *
 * @package Codelicia\Immutable
 */
trait ImmutableProperties
{
    /**
     * @return string[]|ReflectionProperty[]
     */
    private function & reflectionProperty(): array
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

    protected function init(): void
    {
        $reflection = new ReflectionObject($this);
        $store = &$this->reflectionProperty();
        $defaultProperties = $reflection->getDefaultProperties();

        foreach ($reflection->getProperties($this->visibilities()) as $reflectionProperty) {
            $propertyName = $reflectionProperty->name;
            $reflectionProperty->setAccessible(true);
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

    public function __set(string $propertyName, $value)
    {
        $ref = &$this->reflectionProperty();
        $property = $ref[$propertyName];

        if ($property['isInitialized']) {
            throw ImmutableException::mutatingPropertiesAreNotAllowed($propertyName);
        }

        /** @var ReflectionProperty $reflection */
        $reflection = $property['reflection'];

        if ($reflection->hasType()) {
            /** @var ReflectionType $type */
            $type            = $reflection->getType();
            $expectedType    = $type->getName();
            $actualValueType = is_object($value) ? get_class($value) : gettype($value);

            if (is_object($value) && interface_exists($expectedType) && !in_array($expectedType, class_implements($actualValueType), false)) {
                throw TypeErrorExceptionFactory::fromWrongType($this, $propertyName, $expectedType, $actualValueType);
            }

            // TODO: resolve types && !($actualValueType === 'integer' && $expectedType === 'int')
            if ((($expectedType !== $actualValueType && $type->isBuiltin())
                && (null !== $value && !$type->allowsNull()))
                && !($actualValueType === 'integer' && $expectedType === 'int')) {
                throw TypeErrorExceptionFactory::fromWrongType($this, $propertyName, $expectedType, $actualValueType);
            }
        }

        $ref[$propertyName] = [
            'reflection' => $property['reflection'],
            'value' => $value,
            'isInitialized' => true,
        ];
    }

    public function __get(string $propertyName)
    {
        return $this->{$propertyName} ?? $this->reflectionProperty()[$propertyName]['value'];
    }

    public function __debugInfo(): array
    {
        $debug = [];
        foreach ($this->reflectionProperty() as $property => $value) {
            $debug[$property] = $value['value'];
        }

        return $debug;
    }

    public function __isset(string $propertyName): bool
    {
        return array_key_exists($propertyName, $this->reflectionProperty());
    }

    /**
     * In case you want to allow immutability based on property visibility you
     * should overwrite this method picking up the specific visibilities
     * that you want to use.
     */
    public function visibilities(): int
    {
        return ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PRIVATE | ReflectionProperty::IS_PROTECTED;
    }

    public function __destruct()
    {
        $objectHash = spl_object_hash($this);
        $store = &$this->reflectionProperty();

        unset($store[$objectHash]);
    }
}
