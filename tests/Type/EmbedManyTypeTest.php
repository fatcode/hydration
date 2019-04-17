<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Hydration\Type;

use FatCode\Hydration\Exception\HydrationException;
use FatCode\Hydration\Type\EmbedType;
use FatCode\Tests\Hydration\Fixtures\User;
use FatCode\Tests\Hydration\Fixtures\UserName;
use FatCode\Tests\Hydration\Fixtures\UserSchema;
use FatCode\Tests\Hydration\Fixtures\UserWallet;
use PHPUnit\Framework\TestCase;

final class EmbedTypeTest extends TestCase
{
    public function testHydrate() : void
    {
        $type = new EmbedType(new UserSchema());
        /** @var User $hydrated */
        $hydrated = $type->hydrate([
            'id' => 1,
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
            'creationTimeTimezone' => 'UTC',
        ]);

        self::assertInstanceOf(User::class, $hydrated);
        self::assertSame(15, $hydrated->getAge());
        self::assertInstanceOf(UserWallet::class, $hydrated->getWallet());
        self::assertSame('1000.20', $hydrated->getWallet()->getAmount());
        self::assertSame('EUR', $hydrated->getWallet()->getCurrency());
        self::assertInstanceOf(UserName::class, $hydrated->getName());
        self::assertSame('John', $hydrated->getName()->getFirstName());
        self::assertSame('Doe', $hydrated->getName()->getLastName());
    }

    public function testExtract() : void
    {
        $user = new User(new UserName('John', 'Doe'), new UserWallet('GBP', '100.50'));
        $type = new EmbedType(new UserSchema());
        $extracted = $type->extract($user);

        self::assertEquals(
            [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
            $extracted['name']
        );

        self::assertEquals(
            [
                'currency' => 'GBP',
                'amount' => '100.50',
            ],
            $extracted['wallet']
        );

        self::assertArrayHasKey('creationTimeDate', $extracted);
        self::assertArrayHasKey('creationTimeTimezone', $extracted);
    }

    public function testFailOnNullableExtraction() : void
    {
        $this->expectException(HydrationException::class);
        $type = new EmbedType(new UserSchema());
        $type->extract(null);
    }

    public function testExtractNull() : void
    {
        $type = new EmbedType(new UserSchema());
        $type->nullable();
        self::assertNull($type->extract(null));
    }
}
