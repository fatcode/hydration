<?php declare(strict_types=1);

namespace FatCode\Hydration\Type;

class BooleanType implements Type
{
    public function hydrate($value): bool
    {
        return (bool) $value;
    }

    public function extract($value): bool
    {
        return (bool) $value;
    }
}
