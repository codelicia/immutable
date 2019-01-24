Codelicia\Immutable
===================

Install the `ImmutableProperties` library and get to work with immutable properties.

### Installation

```
$ composer req codelicia/immutability --sort-packages
```

### Usage

Enable immutability on your classes just by plugging the `ImmutableProperties` trait on it.

```php
final class User
{
    use Codelicia\ImmutableProperties;

    public string $name;
    public int $age;
}

$user       = new User;
$user->name = "@malukenho";

// NOPE
$user->name = "Throws exception as the property name cannot be reassigned";
```

We recommend you create a `__construct` to make the object stay in a valid state right after the
instantiation of it, but we cannot enforce it on out side, it is up to you and your necessity.

```php
final class User
{
    use Codelicia\ImmutableProperties;

    // It is fine to leave the properties visibility as public as the `ImmutableProperties`
    // trait will not allow it to change after it is being initialized in the
    // class constructor
    public string $name;
    public int $age;

    public function __construct(string $name, int $age)
    {
        $this->init(); // It needs to be initialized here

        $this->name = $name;
        $this->age = $age;
    }
}
```

### Authors

* Jefersson Nathan ([@malukenho](https://github.com/malukenho))
