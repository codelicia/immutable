--TEST--
It should not allow initialization with wrong built in types
--FILE--
<?php
require __DIR__ . '/../../vendor/autoload.php';

class User
{
    use Codelicia\Immutable\ImmutableProperties;

    public int $age;
}

$user = new User;
$user->age = 'malukenho';
?>
--EXPECTF--
Fatal error: Uncaught TypeError: Typed property User::$age must be int, string used %A
