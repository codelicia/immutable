--TEST--
It should not allow to change property after it is initialized
--FILE--
<?php
require __DIR__ . '/../../vendor/autoload.php';

class User
{
    use Codelicia\Immutable\ImmutableProperties;

    public string $name;

    public function __construct(string $name)
    {
        $this->init();

        $this->name = $name;
    }
}

$user = new User('malukenho');

var_dump($user->name);

$user->name = 'other-name';

?>
--EXPECTF--
string(9) "malukenho"

Fatal error: Uncaught RuntimeException: Cannot reassign value to property "name" %A
