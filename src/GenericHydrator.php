<?php declare(strict_types=1);

namespace FatCode\Hydration;

use Closure;
use FatCode\Hydration\Exception\HydrationException;
use FatCode\Hydration\Type\CompositeType;
use FatCode\Hydration\Type\NullableType;
use FatCode\Hydration\Type\Type;

trait GenericHydrator
{
    private function hydrateObject(Schema $schema, array $input, object $object) : object
    {
        $namingStrategy = $schema->getNamingStrategy();

        /** @var Type $type */
        foreach ($schema as $property => $type) {
            if ($type instanceof CompositeType) {
                $values = [];
                foreach ($type->getKeys($property) as $key) {
                    $mappedKey = $namingStrategy->map($key);
                    $values[] = $input[$mappedKey] ?? null;
                }
                if (in_array(null, $values, true)) {
                    if (!$type->isNullable()) {
                        throw HydrationException::forNullHydration($schema, $property);
                    }
                    $this->writeProperty($object, $property, null);
                    continue;
                }
                $this->writeProperty($object, $property, $type->hydrate($values));
                continue;
            }

            $value = $input[$namingStrategy->map($property)] ?? null;
            if ($value === null) {
                if (!$type instanceof NullableType || !$type->isNullable()) {
                    throw HydrationException::forNullHydration($schema, $property);
                }
                continue;
            }

            $this->writeProperty($object, $property, $type->hydrate($value));
        }

        return $object;
    }

    protected function writeProperty(object $object, string $property, $value) : void
    {
        static $writer;
        if ($writer === null) {
            $writer = function ($name, $value) {
                if (!property_exists($this, $name)) {
                    throw HydrationException::forUndeclaredProperty($this, $name);
                }
                $this->$name = $value;
            };
        }

        $set = Closure::bind($writer, $object, $object);
        $set($property, $value);
    }
}
