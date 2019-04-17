<?php declare(strict_types=1);

namespace FatCode\Hydration\Type;

use FatCode\Hydration\Exception\HydrationException;
use FatCode\Hydration\GenericExtractor;
use FatCode\Hydration\GenericHydrator;
use FatCode\Hydration\Instantiator;
use FatCode\Hydration\Schema;

class EmbedType implements Type, NullableType
{
    use Nullable, GenericHydrator, GenericExtractor;

    private $schema;

    public function __construct(Schema $schema)
    {
        $this->schema = $schema;
    }

    public function hydrate($value) : object
    {
        $object = Instantiator::instantiate($this->schema->getTargetClass());
        return $this->hydrateObject($this->schema, $value, $object);
    }

    public function extract($value) : ?array
    {
        if ($value === null) {
            if ($this->nullable) {
                return null;
            }
            throw HydrationException::forUnexpectedNullValue();
        }

        return $this->extractObject($this->schema, $value);
    }
}
