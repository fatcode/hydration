<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Hydration\Type;

use FatCode\Hydration\Type\EmbedManyType;
use FatCode\Tests\Hydration\Fixtures\SimpleUser;
use FatCode\Tests\Hydration\Fixtures\SimpleUserSchema;
use PHPUnit\Framework\TestCase;

final class EmbedManyTypeTest extends TestCase
{
    public function testHydrate() : void
    {
        $type = new EmbedManyType(new SimpleUserSchema());
        $users = $type->hydrate([
            [
                'name' => 'John Doe',
                'email' => 'john.doe@mail.com',
                'age' => 40,
            ],
            [
                'name' => 'Alica Doe',
                'email' => 'alica.doe@mail.com',
                'age' => 30,
            ],
            [
                'name' => 'Ron Doe',
                'email' => 'ron.doe@mail.com',
                'age' => 20,
            ],
        ]);

        self::assertCount(3, $users);
        foreach ($users as $user) {
            self::assertInstanceOf(SimpleUser::class, $user);
        }
    }

    public function testExtract() : void
    {
        $users = [
            new SimpleUser('John Doe', 'john.doe@mail.com', 40),
            new SimpleUser('Alice Doe', 'alice.doe@mail.com', 30),
            new SimpleUser('Ron Doe', 'ron.doe@mail.com', 20),
        ];

        $type = new EmbedManyType(new SimpleUserSchema());
        $extracted = $type->extract($users);

        self::assertCount(3, $extracted);
        foreach ($extracted as $hash) {
            self::assertArrayHasKey('name', $hash);
            self::assertArrayHasKey('email', $hash);
            self::assertArrayHasKey('age', $hash);
        }
    }
}
