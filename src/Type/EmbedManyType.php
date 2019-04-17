<?php declare(strict_types=1);

namespace FatCode\Hydration\Type;

use FatCode\Hydration\Exception\HydrationException;
use FatCode\Hydration\GenericExtractor;
use FatCode\Hydration\GenericHydrator;
use FatCode\Hydration\Instantiator;
use FatCode\Hydration\Schema;

use function is_iterable;

class EmbedManyType implements Type
{
    use GenericHydrator, GenericExtractor;

    private $schema;

    public function __construct(Schema $schema)
    {
        $this->schema = $schema;
    }

    public function hydrate($value) : array
    {
        if (!is_iterable($value)) {
            throw HydrationException::forUnexpectedValue($value, 'iterable');
        }
        $hydrated = [];
        foreach ($value as $index => $entry) {
            $object = Instantiator::instantiate($this->schema->getTargetClass());
            $hydrated[$index] = $object;
            $this->hydrateObject($this->schema, $entry, $object);
        }

        return $hydrated;
    }

    public function extract($value) : array
    {
        if (!is_iterable($value)) {
            throw HydrationException::forUnexpectedValue($value, 'iterable');
        }
        $extracted = [];
        foreach ($value as $index => $object) {
            $extracted[$index] = $this->extractObject($this->schema, $object);
        }

        return $extracted;
    }
}
