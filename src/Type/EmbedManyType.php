<?php declare(strict_types=1);

namespace FatCode\Hydration\Type;

use FatCode\Hydration\GenericExtractor;
use FatCode\Hydration\GenericHydrator;
use FatCode\Hydration\Schema;

class EmbedManyType implements Type
{
    use GenericHydrator, GenericExtractor;

    private $schema;

    public function __construct(Schema $schema)
    {
        $this->schema = $schema;
    }

    public function hydrate($value) : iterable
    {

    }

    public function extract($value) : ?array
    {

    }
}
