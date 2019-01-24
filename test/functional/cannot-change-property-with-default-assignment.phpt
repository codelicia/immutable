--TEST--
It should not allow to change property after it is initialized
--FILE--
<?php
require __DIR__ . '/bootstrap.php';

class User
{
    use Codelicia\Immutable\Immutability;

    public string $name = 'malukenho';
}

$user = new User;
var_dump($user->name);

$user->name = 'other-name';

?>
--EXPECTF--
string(9) "malukenho"

Fatal error: Uncaught RuntimeException: Cannot reassign value to property "name" %A
