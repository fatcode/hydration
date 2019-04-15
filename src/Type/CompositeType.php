<?php declare(strict_types=1);

namespace FatCode\Hydration\Type;

interface CompositeType extends NullableType
{
    public function getKeys(string $prefix) : array;
}
