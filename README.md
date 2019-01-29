Codelicia\Immutable
===================

Enforces immutability on initialized typed properties of any PHP object.

Immutable properties can be really handful to avoid `getter/setter` boiler-plates
or just enforce immutability for specific objects.

We see it as specially useful for the following use cases: 
**VOs**, **DTOs**, **Commands**, **Events** and **ViewModels**.

### Installation

```
$ composer require codelicia/immutable
```

### Usage

Enable immutability on your classes just by plugging the `ImmutableProperties` trait on it.

```php
final class User
{
    use \Codelicia\ImmutableProperties;

    public string $name;
    public int $age;
}

$user       = new User;
$user->name = "@malukenho";

// this will crash
$user->name = "Throws exception as the property name cannot be reassigned";
```

We recommend you create a `__construct` to make the object stay in a valid state
right after the instantiation of it, but we cannot enforce it on out side, it is
up to you and your necessity.

```php
final class User
{
    use \Codelicia\ImmutableProperties;

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

### Working with custom visibilities

If you want to make properties immutable based on their visibilities, you can create a
new trait and overwrite the `affectedVisibilities()` method with your custom affected
properties.

### Authors

* Jefersson Nathan ([@malukenho](https://github.com/malukenho))
