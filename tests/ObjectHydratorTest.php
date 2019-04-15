<?php declare(strict_types=1);

namespace FatCode\Tests\Hydration;

use FatCode\Hydration\Exception\SchemaException;
use FatCode\Hydration\Instantiator;
use FatCode\Hydration\ObjectHydrator;
use FatCode\Hydration\Schema;
use FatCode\Hydration\SchemaLoader;
use FatCode\Tests\Hydration\Fixtures\User;
use FatCode\Tests\Hydration\Fixtures\UserName;
use FatCode\Tests\Hydration\Fixtures\UserSchema;
use FatCode\Tests\Hydration\Fixtures\UserWallet;
use PHPUnit\Framework\TestCase;

final class ObjectHydratorTest extends TestCase
{
    public function testRegisterSchema() : void
    {
        $schema = new UserSchema();
        $objectHydrator = new ObjectHydrator();
        $objectHydrator->addSchema($schema);

        self::assertSame($schema, $objectHydrator->getSchema($schema->getTargetClass()));
    }

    public function testRegisterLoader() : void
    {
        $objectHydrator = new ObjectHydrator();
        self::assertFalse($objectHydrator->hasSchema(User::class));

        $loader = new class implements SchemaLoader {
            public function load(string $class): ?Schema
            {
                if ($class === User::class) {
                    return new UserSchema();
                }

                return null;
            }
        };
        $objectHydrator->addSchemaLoader($loader);
        self::assertTrue($objectHydrator->hasSchema(User::class));
        self::assertInstanceOf(UserSchema::class, $objectHydrator->getSchema(User::class));
    }

    public function testFailGetOnUndefinedSchema() : void
    {
        $this->expectException(SchemaException::class);
        $objectHydrator = new ObjectHydrator();
        $objectHydrator->getSchema('Something');
    }

    public function testHydrate() : void
    {
        $objectHydrator = new ObjectHydrator();
        $objectHydrator->addSchema(new UserSchema());
        $id = 1;
        $user = $objectHydrator->hydrate(
            [
                'id' => $id,
                'name' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                ],
                'wallet' => [
                    'amount' => '1000.20',
                    'currency' => 'EUR',
                ],
                'age' => '15',
                'creationTimeDate' => time(),
                'creationTimeTimezone' => 'Europe/Berlin',
            ],
            Instantiator::instantiate(User::class)
        );

        self::assertInstanceOf(User::class, $user);
        self::assertSame(15, $user->getAge());
        self::assertInstanceOf(UserWallet::class, $user->getWallet());
        self::assertSame('1000.20', $user->getWallet()->getAmount());
        self::assertSame('EUR', $user->getWallet()->getCurrency());
        self::assertInstanceOf(UserName::class, $user->getName());
        self::assertSame('John', $user->getName()->getFirstName());
        self::assertSame('Doe', $user->getName()->getLastName());
    }

    public function testExtract() : void
    {
        $user = new User(new UserName('John', 'Doe'), new UserWallet('USD', '10.00'));
        $id = $user->getId();
        $objectHydrator = new ObjectHydrator();
        $objectHydrator->addSchema(new UserSchema());

        $extracted = $objectHydrator->extract($user);

        self::assertEquals(
            [
                'id' => $id,
                'name' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                ],
                'age' => 0,
                'favouriteNumber' => '0.00',
                'language' => '',
                'email' => '',
                'wallet' => [
                    'currency' => 'USD',
                    'amount' => '10.00',
                ],
                'eyeColor' => '',
                'creationTimeDate' => $user->getCreationTime()->getTimestamp(),
                'creationTimeTimezone' => 'UTC',
            ],
            $extracted
        );
    }
}
