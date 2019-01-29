<?php

declare(strict_types=1);

namespace CodeliciaTest\Immutable;

use Codelicia\Immutable\PropertyState;
use CodeliciaTest\Immutable\unit\DummyClassWithAllTypes;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionProperty;

final class PropertyStateTest extends TestCase
{
    /**
     * @test
     * @throws ReflectionException
     */
    public function it_should_be_able_to_hold_state() : void
    {
        $reflectionProperty = new ReflectionProperty(DummyClassWithAllTypes::class, 'string');
        $propertyState      = PropertyState::createUninitializedValue($reflectionProperty, 'ID-VALUE', false);


        self::assertFalse($propertyState->isInitialized());
        self::assertSame('ID-VALUE', $propertyState->value());
        self::assertSame($reflectionProperty, $propertyState->reflectionProperty());
    }

    /**
     * @test
     * @throws ReflectionException
     */
    public function it_should_be_able_to_create_an_initialized_property_state() : void
    {
        $reflectionProperty = new ReflectionProperty(DummyClassWithAllTypes::class, 'string');
        $propertyState      = PropertyState::createInitializedValue($reflectionProperty, 'ID-VALUE');

        self::assertTrue($propertyState->isInitialized());
        self::assertSame('ID-VALUE', $propertyState->value());
        self::assertSame($reflectionProperty, $propertyState->reflectionProperty());
    }
}
