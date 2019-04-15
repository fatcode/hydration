<?php declare(strict_types=1);

namespace FatCode\Tests\Hydration\Fixtures;

final class SimpleUser
{
    private $name;
    private $email;
    private $age;

    public function __construct(UserName $name, string $email, int $age)
    {
        $this->name = $name;
        $this->email = $email;
        $this->age = $age;
    }
}
