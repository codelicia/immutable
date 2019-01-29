<?php

declare(strict_types=1);

namespace CodeliciaTest\Immutable;

use Codelicia\Immutable\TypeErrorExceptionFactory;
use PHPUnit\Framework\TestCase;
use SplStack;

final class TypeErrorExceptionFactoryTest extends TestCase
{
    /** @test */
    public function it_should_return_type_error_message() : void
    {
        $exception = TypeErrorExceptionFactory::fromWrongType(new SplStack(), 'id', 'int', 'string');

        self::assertSame('Typed property SplStack::$id must be int, string used', $exception->getMessage());
    }
}
