<?php

declare(strict_types=1);

namespace CodeliciaTest\Immutable\unit;

use stdClass;

final class DummyClassWithAllTypes
{
    public int $int;
    public ?int $nullable_int;
    public string $string;
    public object $object;
    public stdClass $class;
    public float $float;
    public bool $bool;
    public array $array;
    public iterable $iterable;
    public $noType;
}
