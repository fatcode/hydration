<?php declare(strict_types=1);

namespace FatCode\Hydration;

interface Hydrator
{
    public function hydrate(array $hash, object $object) : object;
}
