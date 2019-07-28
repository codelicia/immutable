--TEST--
It should not allow to change property after it is initialized
--FILE--
<?php
require __DIR__ . '/../../vendor/autoload.php';

class User
{
    use Codelicia\Immutable\ImmutableProperties;

    public string $name;
}

$user = new User;
$user->name = 'malukenho';

var_dump($user->name);

$user->name = 'other-name';

?>
--EXPECTF--
string(9) "malukenho"

Fatal error: Uncaught Codelicia\Immutable\ImmutablePropertiesException: Cannot reassign value to property "name" %A
