<?php declare(strict_types=1);

namespace FatCode\Tests\Hydration;

use Error;
use FatCode\Hydration\Type;
use FatCode\Hydration\Type\SerializationMethod;
use PHPUnit\Framework\TestCase;

final class TypeTest extends TestCase
{
    public function testCantInstantiate() : void
    {
        $this->expectException(Error::class);
        $type = new Type();
    }

    public function testInteger() : void
    {
        $type = Type::integer();
        self::assertSame($type, Type::integer());
    }

    public function testString() : void
    {
        $type = Type::string();
        self::assertSame($type, Type::string());
    }

    public function testFloat() : void
    {
        $type = Type::float();
        self::assertSame($type, Type::float());
    }

    public function testBoolean() : void
    {
        $type = Type::boolean();
        self::assertSame($type, Type::boolean());
    }

    public function testId() : void
    {
        $type = Type::id();
        self::assertNotSame($type, Type::id());
    }

    public function testArray() : void
    {
        $type = Type::array();
        self::assertSame($type, Type::array());

        $typeWithAttribute = Type::array(SerializationMethod::JSON());
        self::assertNotSame($typeWithAttribute, $type);
        self::assertNotSame($typeWithAttribute, Type::array());
        self::assertSame($typeWithAttribute, Type::array(SerializationMethod::JSON()));
    }

    public function testDate() : void
    {
        $type = Type::date();
        self::assertSame($type, Type::date());
    }
}
