<?php declare(strict_types=1);

namespace FatCode\Hydration\Type;

interface NullableType extends Type
{
    public function nullable() : Type;
    public function isNullable() : bool;
}
