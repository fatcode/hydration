<?php declare(strict_types=1);

namespace FatCode\Tests\Storage\Hydration;

use FatCode\Hydration\Exception\HydrationException;
use FatCode\Hydration\Instantiator;
use FatCode\Tests\Hydration\Fixtures\User;
use FatCode\Tests\Hydration\Fixtures\UserName;
use PHPUnit\Framework\TestCase;
use SimpleXMLElement;

final class InstantiatorTest extends TestCase
{
    public function testInstantiate() : void
    {
        self::assertInstanceOf(User::class, Instantiator::instantiate(User::class));
        self::assertInstanceOf(User::class, Instantiator::instantiate(User::class));
        self::assertInstanceOf(UserName::class, Instantiator::instantiate(UserName::class));
    }

    public function testInstantiateInvalidClass() : void
    {
        $this->expectException(HydrationException::class);
        Instantiator::instantiate('____NonExistingClass_____');
    }

    public function testFailInstantiationOnBuiltInClass() : void
    {
        $this->expectException(HydrationException::class);
        Instantiator::instantiate(SimpleXMLElement::class);
    }
}
