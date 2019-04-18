# Hydration [![Build Status](https://travis-ci.org/fatcode/hydration.svg?branch=master)](https://travis-ci.org/fatcode/hydration) [![Maintainability](https://api.codeclimate.com/v1/badges/80b307b6f031ce108da9/maintainability)](https://codeclimate.com/github/fatcode/hydration/maintainability) [![Test Coverage](https://api.codeclimate.com/v1/badges/80b307b6f031ce108da9/test_coverage)](https://codeclimate.com/github/fatcode/hydration/test_coverage)

## Installation
`composer require fatcode/hydration`

## Hydration

Hydration is a process of populating object from a set of data. Storage library provides mechanisms and interfaces
for both hydrating and extracting data sets.

### Schemas

Schema is an object describing how dataset should be hydrated. Schemas should be registered in `\FatCode\Hydration\ObjectHydrator`, so
your data can be easily hydrated/extracted.

```php
<?php
use FatCode\Hydration\Schema;
use FatCode\Hydration\Type;

class MyUser 
{
    private $id;
    private $name;
    private $age;
    private $interests = [];
    
    public function __construct(int $id, string $name, int $age) 
    {
        $this->id = $id;
        $this->name = $name;
        $this->age = $age;
    }
}

class MyUserSchema extends Schema
{
    protected $id;
    protected $name;
    protected $age;
    protected $interests;
    
    public function __construct()
    {
        $this->id = Type::id();
        $this->name = Type::string();
        $this->age = Type::integer();
        $this->interests = Type::array();
    }
    
    // Target class has to be provided so schema knows to which class it is corresponding.
    public function getTargetClass() : string
    {
        return MyUser::class;
    }
}
```

 > Note: Please make sure that your schema properties are either protected or public, so they can be accessed from
 > the parent class. Private properties will not be recognized and this can result in `null` values or errors.

Above we have defined domain class `MyUser` and corresponding schema class `MyUserSchema`. 
Having this setup we can start hydration/extraction process.

### Hydration with `ObjectHydrator`

`\FatCode\Hydration\ObjectHydrator` is at the same time registry for all your schema classes and general purpose 
hydrator/extractor functionality provider. This may sound like a lot responsibility is put into the class but keep
in mind that hydration/extraction process must be described by schema before it can happen and `ObjectHydrator` 
provides utilities to simplify your workflow with extraction/hydration and schema loading.

In order to hydrate/extract object, a schema must be recognized by `ObjectHydrator`. There are two ways of doing it:
- passing schema to `\FatCode\Hydration\ObjectHydrator::addSchema` method
- implementing and passing instance of `\FatCode\Hydration\SchemaLoader` to `\FatCode\Hydration\ObjectHydrator::addSchemaLoader` method

For now on we will focus on the first one.

#### Registering schema in the `ObjectHydrator`

```php
<?php
use FatCode\Hydration\ObjectHydrator;

$objectHydrator = new ObjectHydrator();
$objectHydrator->addSchema(new MyUserSchema());
```

The above code registers schema presented in the previous chapter. 
From this point on any instance of `MyUser` class can be hydrated or extracted with `ObjectHydrator`.

### Hydrating objects

```php
<?php
use FatCode\Hydration\ObjectHydrator;

$objectHydrator = new ObjectHydrator();
$objectHydrator->addSchema(new MyUserSchema());

// Hydration
$bob = $objectHydrator->hydrate(
    [
        'id' => 1,
        'name' => 'Bob',
        'age' => 30,
        'interests' => ['Flowers', 'Judo', 'M1lf$']
    ], 
    MyUser::class
);
```
 
### Extracting objects
 
```php
<?php
use FatCode\Hydration\ObjectHydrator;

$objectHydrator = new ObjectHydrator();
$objectHydrator->addSchema(new MyUserSchema());
$bob = new MyUser(1, 'Bob', 30);
$dataset = $objectHydrator->extract($bob); // ['id' => 1, 'name' => 'Bob', 'age' => 30, 'interests' => []]
```
