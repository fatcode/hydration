<?php declare(strict_types=1);

namespace FatCode\Hydration\NamingStrategy;

use function ltrim;
use function strtolower;
use function preg_replace;

class UnderscoreNaming implements NamingStrategy
{
    public function map(string $name) : string
    {
        return $this->camelCaseToUnderscore($name);
    }

    private function camelCaseToUnderscore(string $input): string
    {
        return strtolower(ltrim(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $input), '_'));
    }
}
