--TEST--
It should allow interfaces as proper types
--FILE--
<?php
require __DIR__ . '/../../vendor/autoload.php';

interface Email {}
class Gmail implements Email {}

class User
{
    use Codelicia\Immutable\ImmutableProperties;

    public Email $email;
}

$user = new User;
$user->email = new Gmail;

var_dump($user);
?>
--EXPECTF--
object(User)#3 (1) {
  ["email"]=>
  object(Gmail)#5 (0) {
  }
}
