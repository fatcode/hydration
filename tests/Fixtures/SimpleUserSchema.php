<?php declare(strict_types=1);

namespace FatCode\Tests\Hydration\Fixtures;

use FatCode\Hydration\Schema;
use FatCode\Hydration\Type;

final class SimpleUserSchema extends Schema
{
    protected $name;
    protected $email;
    protected $age;

    public function __construct()
    {
        $this->name = Type::string();
        $this->email = Type::string();
        $this->age = Type::integer();
    }

    public function getTargetClass() : string
    {
        return SimpleUser::class;
    }
}
