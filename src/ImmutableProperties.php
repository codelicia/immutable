<?php

declare(strict_types=1);

namespace Codelicia\Immutable;

use ReflectionObject;
use ReflectionProperty;
use ReflectionType;
use function array_key_exists;
use function get_class;
use function gettype;
use function is_object;
use function spl_object_hash;

/**
 * It manage the state of the properties to make sure it doesn't change.
 * The properties are store under a static variable `$reflectionProperties`
 * inside `self#reflectionProperty()` where reference to objects are
 * stored along with the necessary data to make those checks.
 *
 * @package Codelicia\Immutable
 */
trait ImmutableProperties
{
    /**
     * @return PropertyState[]
     */
    private function & referenceCollection() : array
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

    protected function init() : void
    {
        $reflection        = new ReflectionObject($this);
        $store             = &$this->referenceCollection();
        $defaultProperties = $reflection->getDefaultProperties();

        foreach ($reflection->getProperties($this->affectedVisibilities()) as $reflectionProperty) {
            $propertyName = $reflectionProperty->name;
            $reflectionProperty->setAccessible(true);
            /** @var ReflectionType $type */
            $type = $reflectionProperty->getType();

            $store[$propertyName] = PropertyState::createUninitializedValue(
                $reflectionProperty,
                $defaultProperties[$propertyName] ?? null,
                isset($defaultProperties[$propertyName]) ? true : $reflectionProperty->isInitialized($this)
            );

            if (isset($defaultProperties[$propertyName]) || $type->allowsNull()) {
                unset($this->{$propertyName});
            }
        }
    }

    public function __set(string $propertyName, $value)
    {
        $references = &$this->referenceCollection();
        $property   = $references[$propertyName];

        if ($property->isInitialized()) {
            throw ImmutableException::mutatingPropertiesAreNotAllowed($propertyName);
        }

        $reflection = $property->reflectionProperty();

        if ($reflection->hasType()) {
            /** @var ReflectionType $type */
            $type            = $reflection->getType();
            $expectedType    = $type->getName();
            $actualValueType = is_object($value) ? get_class($value) : gettype($value);

            if (! TypesMatchResolver::resolve($value, $type)) {
                throw TypeErrorExceptionFactory::fromWrongType($this, $propertyName, $expectedType, $actualValueType);
            }
        }

        $references[$propertyName] = PropertyState::createInitializedValue($reflection, $value);
    }

    public function __get(string $propertyName)
    {
        return $this->{$propertyName} ?? $this->referenceCollection()[$propertyName]->value();
    }

    public function __debugInfo() : array
    {
        $debug = [];
        foreach ($this->referenceCollection() as $property => $value) {
            $debug[$property] = $this->{$property} ?? $value->value();
        }

        return $debug;
    }

    public function __isset(string $propertyName) : bool
    {
        return array_key_exists($propertyName, $this->referenceCollection());
    }

    private function affectedVisibilities() : int
    {
        return ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PRIVATE | ReflectionProperty::IS_PROTECTED;
    }

    final public function __destruct()
    {
        $objectHash = spl_object_hash($this);
        $store      = &$this->referenceCollection();

        unset($store[$objectHash]);
    }
}
