--TEST--
It should allow changes on an immutable array
--FILE--
<?php
require __DIR__ . '/../../vendor/autoload.php';

$immutableArray = new Codelicia\Immutable\ImmutableArray([40, 30, 20, 10]);

try {
    $immutableArray[0] = 40;
} catch (Codelicia\Immutable\ImmutableArrayException $e) {
    var_dump($e->getMessage());
}

try {
    $immutableArray[2] = 50;
} catch (Codelicia\Immutable\ImmutableArrayException $e) {
    var_dump($e->getMessage());
}

try {
    unset($immutableArray[1]);
} catch (Codelicia\Immutable\ImmutableArrayException $e) {
    var_dump($e->getMessage());
}
--EXPECTF--
string(32) "Cannot change an immutable array"
string(32) "Cannot change an immutable array"
string(32) "Cannot change an immutable array"
