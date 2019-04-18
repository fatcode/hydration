<?php declare(strict_types=1);

namespace FatCode\Hydration;

use Closure;
use DateTimeZone;
use FatCode\Hydration\Type\ArrayType;
use FatCode\Hydration\Type\BooleanType;
use FatCode\Hydration\Type\DateTimeType;
use FatCode\Hydration\Type\DateType;
use FatCode\Hydration\Type\DecimalType;
use FatCode\Hydration\Type\EmbedManyType;
use FatCode\Hydration\Type\EmbedType;
use FatCode\Hydration\Type\FloatType;
use FatCode\Hydration\Type\IdType;
use FatCode\Hydration\Type\IntegerType;
use FatCode\Hydration\Type\SerializationMethod;
use FatCode\Hydration\Type\StringType;

/**
 * Class Property
 * @package FatCode\Storage\Hydration
 */
final class Type
{
    /** @var Type\Type[] */
    private static $cachedTypes = [];

    private function __construct()
    {
        // Prevent for instantiation this class
    }

    public static function integer() : IntegerType
    {
        return self::getCached('integer', function () {
            return new IntegerType();
        });
    }

    public static function string() : StringType
    {
        return self::getCached('string', function () {
            return new StringType();
        });
    }

    public static function float() : FloatType
    {
        return self::getCached('float', function () {
            return new FloatType();
        });
    }

    public static function boolean() : BooleanType
    {
        return self::getCached('boolean', function () {
            return new BooleanType();
        });
    }

    public static function id() : IdType
    {
        return new IdType();
    }

    public static function array(SerializationMethod $method = null) : ArrayType
    {
        $method = $method ?? SerializationMethod::NONE();
        return self::getCached("array:{$method->getValue()}", function () use ($method) {
            return new ArrayType($method);
        });
    }

    public static function decimal(int $scale = 2, int $precision = 10) : DecimalType
    {
        return self::getCached("decimal:{$scale},{$precision}", function () use ($scale, $precision) {
            return new DecimalType($scale, $precision);
        });
    }

    public static function date(string $format = 'Ymd') : DateType
    {
        return self::getCached("date:{$format}", function () use ($format) {
            return new DateType($format);
        });
    }

    public static function embed(Schema $schema) : EmbedType
    {
        return new EmbedType($schema);
    }

    public static function embedMany(Schema $schema) : EmbedManyType
    {
        return new EmbedManyType($schema);
    }

    public static function dateTime(DateTimeZone $defaultTimeZone = null) : DateTimeType
    {
        return new DateTimeType($defaultTimeZone);
    }

    private static function getCached(string $key, Closure $factory = null)
    {
        if (isset(self::$cachedTypes[$key])) {
            return self::$cachedTypes[$key];
        }

        return self::$cachedTypes[$key] = $factory();
    }
}
