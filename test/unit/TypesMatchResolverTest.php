<?php

declare(strict_types=1);

namespace CodeliciaTest\Immutable;

use ArrayIterator;
use Codelicia\Immutable\TypesMatchResolver;
use CodeliciaTest\Immutable\unit\DummyClassWithAllTypes;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionType;
use ReflectionException;
use SplStack;

final class TypesMatchResolverTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideReflectionTypes
     */
    public function it_should_match_types($value, ReflectionType $reflectionType) : void
    {
        self::assertTrue(TypesMatchResolver::resolve($value, $reflectionType));
    }

    /**
     * @throws ReflectionException
     */
    public function provideReflectionTypes() : iterable
    {
        $reflection = new ReflectionClass(DummyClassWithAllTypes::class);

        // TODO missing add tests for "nullable" data types
        yield [1, $reflection->getProperty('int')->getType()];
        yield ['string value', $reflection->getProperty('string')->getType()];
        yield [new SplStack(), $reflection->getProperty('object')->getType()];
        yield [12.1, $reflection->getProperty('float')->getType()];
        yield [12E13, $reflection->getProperty('float')->getType()];
        yield [false, $reflection->getProperty('bool')->getType()];
        yield [true, $reflection->getProperty('bool')->getType()];
        yield [[], $reflection->getProperty('array')->getType()];
        yield [[], $reflection->getProperty('iterable')->getType()];
        yield [new ArrayIterator([]), $reflection->getProperty('iterable')->getType()];
    }
}
