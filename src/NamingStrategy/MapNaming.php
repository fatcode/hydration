<?php declare(strict_types=1);

namespace FatCode\Hydration\NamingStrategy;

class MapNaming implements NamingStrategy
{
    private $map;

    public function __construct(array $map)
    {
        $this->map = $map;
    }

    public function map(string $name) : string
    {
        return $this->map[$name] ?? $name;
    }
}
