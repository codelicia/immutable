--TEST--
It should create immutable array from classic array
--FILE--
<?php
require __DIR__ . '/../../vendor/autoload.php';

$immutableArray = new Codelicia\Immutable\ImmutableArray([40, 30, 20, 10]);

echo "Array content:" . PHP_EOL;
foreach ($immutableArray as $key => $value) {
    echo $key . ": " . $value  . PHP_EOL;
}

echo "Second item: " . $immutableArray[1] . PHP_EOL;

echo "Last item: " . end($immutableArray) . PHP_EOL;
--EXPECTF--
Array content:
0: 40
1: 30
2: 20
3: 10
Second item: 30
Last item: 4
