--TEST--
It should not allow to change private property after it is initialized
--FILE--
<?php
require __DIR__ . '/../../vendor/autoload.php';

class User
{
    use Codelicia\Immutable\ImmutableProperties;

    private string $name;

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}

$user = new User;
$user->setName('malukenho');

var_dump($user->getName());

$user->setName('other-name');

?>
--EXPECTF--
string(9) "malukenho"

Fatal error: Uncaught Codelicia\Immutable\ImmutablePropertiesException: Cannot reassign value to property "name" %A
