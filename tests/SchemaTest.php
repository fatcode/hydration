<?php declare(strict_types=1);

namespace FatCode\Tests\Hydration;

use FatCode\Hydration\NamingStrategy\NamingStrategy;
use FatCode\Hydration\Type\IdType;
use FatCode\Hydration\Type\IntegerType;
use FatCode\Hydration\Type\StringType;
use FatCode\Hydration\Type\Type;
use FatCode\Tests\Hydration\Fixtures\UserSchema;
use PHPUnit\Framework\TestCase;

final class SchemaTest extends TestCase
{
    public function testGetProperties() : void
    {
        $schema = new UserSchema();
        $properties = $schema->getProperties();

        self::assertCount(9, $schema);
        self::assertInstanceOf(IdType::class, $properties['id']);
        self::assertInstanceOf(StringType::class, $properties['language']);
        self::assertInstanceOf(StringType::class, $properties['email']);
        self::assertInstanceOf(IntegerType::class, $properties['age']);
        self::assertInstanceOf(StringType::class, $properties['eyeColor']);
        self::assertTrue($schema->definesId());
    }

    public function testIterateThroughSchema() : void
    {
        $schema = new UserSchema();

        foreach ($schema as $name => $property) {
            self::assertInstanceOf(Type::class, $property);
        }
    }

    public function testGetNamingStrategy() : void
    {
        $schema = new UserSchema();

        self::assertInstanceOf(NamingStrategy::class, $schema->getNamingStrategy());
    }
}
