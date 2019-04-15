<?php declare(strict_types=1);

namespace FatCode\Hydration\Type;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

class DateTimeType implements CompositeType
{
    use Nullable;
    
    private const DATE_PART = 'Date';
    private const TIMEZONE_PART = 'Timezone';

    private $defaultTimezone;

    public function __construct(DateTimeZone $defaultTimezone = null)
    {
        $this->defaultTimezone = $defaultTimezone ?? new DateTimeZone('UTC');
    }

    public function hydrate($value): DateTimeInterface
    {
        $time = new DateTimeImmutable("@{$value[0]}");
        return $time->setTimezone(isset($value[1]) ? new DateTimeZone($value[1]) : $this->defaultTimezone);
    }

    /**
     * @param DateTimeInterface|null $object
     * @return array
     */
    public function extract($object): array
    {
        if ($object === null) {
            return [null, $this->defaultTimezone];
        }

        return [
            $object->getTimestamp(),
            $object->getTimezone()->getName()
        ];
    }

    public function getKeys(string $prefix): array
    {
        return [
            $prefix . self::DATE_PART,
            $prefix . self::TIMEZONE_PART,
        ];
    }
}
