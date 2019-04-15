<?php declare(strict_types=1);

namespace FatCode\Hydration\Exception;

use FatCode\Hydration\Type\Type;

class TypeException extends \RuntimeException implements Exception
{
    public static function forUnknownType(string $type): self
    {
        return new self("Invalid type specified:  {$type}");
    }

    public static function forValidationFailure(Type $type, string $message = ''): self
    {
        $class = get_class($type);
        return new self("Failed to instantiate type {$class}. {$message}");
    }

    public static function forInvalidTypeRegister(string $type): self
    {
        return new self("Registered type {$type} does not implement required" . Type::class . ' interface');
    }

    public static function forUnknownEmbedClass(string $class) : self
    {
        return new self("Cannot use {$class} as embedded type, class not found.");
    }
}
