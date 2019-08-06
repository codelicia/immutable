<?php

declare(strict_types=1);

namespace CodeliciaTest\Immutable;

use Codelicia\Immutable\ImmutableArray;
use Codelicia\Immutable\ImmutableArrayException;
use PHPUnit\Framework\TestCase;

final class ArrayTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_build_an_immutable_array(): void
    {
        $array = [1,2,3];

        $immutableArray = new ImmutableArray($array);

        self::assertInstanceOf(ImmutableArray::class, $immutableArray);
        self::assertEquals($array[0], $immutableArray[0]);
        self::assertEquals($array[1], $immutableArray[1]);
        self::assertEquals($array[2], $immutableArray[2]);
    }

    /**
     * @test
     */
    public function it_should_check_if_item_exists(): void
    {
        $immutableArray = new ImmutableArray(['first', 'second']);

        self::assertTrue(isset($immutableArray[0]));
        self::assertTrue(isset($immutableArray[1]));
        self::assertFalse(isset($immutableArray[2]));
    }

    /**
     * @test
     */
    public function it_should_prevent_to_change_an_item(): void
    {
        $immutableArray = new ImmutableArray(['first', 'last']);

        self::expectException(ImmutableArrayException::class);
        self::expectExceptionMessage('Cannot change an immutable array');

        $immutableArray[1] = 'second'; 
    }

    /**
     * @test
     */
    public function it_should_prevent_to_set_an_item(): void
    {
        $immutableArray = new ImmutableArray(['first', 'last']);

        self::expectException(ImmutableArrayException::class);
        self::expectExceptionMessage('Cannot change an immutable array');

        $immutableArray[] = 'another'; 
    }

    /**
     * @test
     */
    public function it_should_prevent_to_unset_an_item(): void
    {
        $immutableArray = new ImmutableArray(['first', 'last']);

        self::expectException(ImmutableArrayException::class);
        self::expectExceptionMessage('Cannot change an immutable array');

        unset($immutableArray[0]);
    }

    /**
     * @test
     */
    public function it_should_be_iterable(): void
    {
        $immutableArray = new ImmutableArray(['a', 'b', 'c']);
        $expectedArray = [];

        foreach ($immutableArray as $key => $value) {
            $expectedArray[$key] = $value;
        }

        self::assertSame([0 => 'a', 1 => 'b', 2 => 'c'], $expectedArray);
    }
}
