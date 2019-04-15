<?php declare(strict_types=1);

namespace FatCode\Hydration;

interface SchemaLoader
{
    public function load(string $class): ?Schema;
}
