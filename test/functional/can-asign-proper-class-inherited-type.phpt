--TEST--
It should allow inherit members as proper types
--FILE--
<?php
require __DIR__ . '/bootstrap.php';

class Email {}
class Gmail extends Email {}
class Inbox extends Gmail {}

class User
{
    use Codelicia\Immutable\Immutability;

    public Email $email;
}

$user = new User;
$user->email = new Inbox;

var_dump($user);
?>
--EXPECTF--
object(User)#3 (1) {
  ["email"]=>
  object(Inbox)#5 (0) {
  }
}
