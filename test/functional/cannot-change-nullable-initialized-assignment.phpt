--TEST--
It should allow null on nullable types
--FILE--
<?php
require __DIR__ . '/bootstrap.php';

class User
{
    use Codelicia\Immutable\ImmutableProperties;

    public ?string $name;
}

$user = new User;

var_dump($user->name);

$user->name = null;

var_dump($user);

$user->name = 'malukenho';

?>
--EXPECTF--
NULL
object(User)#3 (1) {
  ["name"]=>
  NULL
}

Fatal error: Uncaught RuntimeException: Cannot reassign value to property "name" %A
