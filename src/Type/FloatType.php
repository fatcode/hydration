<?php declare(strict_types=1);

namespace FatCode\Hydration\Type;

class FloatType implements Type, NullableType
{
    use Nullable;

    public function hydrate($value): float
    {
        return (float) $value;
    }

    public function extract($value): float
    {
        return (float) $value;
    }
}
