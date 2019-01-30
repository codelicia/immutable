<?php

declare(strict_types=1);

namespace Codelicia\Immutable;

use ReflectionProperty;

/**
 * @internal
 */
final class PropertyState
{
    /** @var ReflectionProperty */
    private $reflectionProperty;

    /** @var mixed */
    private $value;

    /** @var bool */
    private $isInitialized;

    private function __construct(ReflectionProperty $reflectionProperty, $value, bool $isInitialized)
    {
        $this->reflectionProperty = $reflectionProperty;
        $this->value              = $value;
        $this->isInitialized      = $isInitialized;
    }

    public static function createUninitializedValue(ReflectionProperty $reflectionProperty, $value, bool $isInitialized): self
    {
        return new self($reflectionProperty, $value, $isInitialized);
    }

    public static function createInitializedValue(ReflectionProperty $reflectionProperty, $value): self
    {
        return new self($reflectionProperty, $value, true);
    }

    public function isInitialized(): bool
    {
        return $this->isInitialized;
    }

    public function reflectionProperty(): ReflectionProperty
    {
        return $this->reflectionProperty;
    }

    public function value()
    {
        return $this->value;
    }
}
