<?php declare(strict_types=1);

namespace FatCode\Hydration;

use FatCode\Hydration\Exception\SchemaException;

use function in_array;
use function get_class;
use function is_object;
use function is_string;

final class ObjectHydrator implements Hydrator, Extractor
{
    use GenericHydrator, GenericExtractor;

    /** @var Schema[] */
    private $schemaRegistry = [];

    /** @var SchemaLoader[] */
    private $schemaLoaders = [];

    public function hydrate(array $hash, $classNameOrInstance) : object
    {
        if (is_string($classNameOrInstance)) {
            $schema = $this->getSchema($classNameOrInstance);
            $instance = Instantiator::instantiate($classNameOrInstance);
        } elseif (is_object($classNameOrInstance)) {
            $schema = $this->getSchema(get_class($classNameOrInstance));
            $instance = $classNameOrInstance;
        } else {
            throw SchemaException::forUndefinedSchema('');
        }

        return $this->hydrateObject($schema, $hash, $instance);
    }

    public function extract(object $object): array
    {
        $className = get_class($object);
        return $this->extractObject($this->getSchema($className), $object);
    }

    public function addSchemaLoader(SchemaLoader $loader) : void
    {
        if (in_array($loader, $this->schemaLoaders, true)) {
            return;
        }
        $this->schemaLoaders[] = $loader;
    }

    public function addSchema(Schema $schema) : void
    {
        $this->schemaRegistry[$schema->getTargetClass()] = $schema;
    }

    public function hasSchema(string $class) : bool
    {
        return isset($this->schemaRegistry[$class]) || $this->loadSchema($class);
    }

    public function getSchema(string $class) : Schema
    {
        if (!$this->hasSchema($class)) {
            throw SchemaException::forUndefinedSchema($class);
        }

        return $this->schemaRegistry[$class];
    }

    private function loadSchema(string $class) : bool
    {
        foreach ($this->schemaLoaders as $loader) {
            $schema = $loader->load($class);
            if ($schema !== null) {
                $this->schemaRegistry[$class] = $schema;
                return true;
            }
        }

        return false;
    }
}
