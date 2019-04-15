<?php declare(strict_types=1);

namespace FatCode\Hydration\Exception;

class SchemaException extends \RuntimeException implements Exception
{
    public static function forMissingIdentifier(string $class): self
    {
        return new self("Schema definition for {$class} contains no identifier. Please add IdProperty to the schema.");
    }

    public static function forMissingProperty(string $class, string $property): self
    {
        return new self("Schema definition for {$class} contains no {$property} property.");
    }

    public static function forUndefinedSchema(string $class) : self
    {
        return new self("Schema definition for {$class} could not be loaded.");
    }
}
