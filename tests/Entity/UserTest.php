<?php

/**
 * tests/Entity/UserTest.php
 *
 * @category EntityTests
 * @package  MiW\Results\Tests
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

namespace MiW\Results\Tests\Entity;

use MiW\Results\Entity\User;
use PHPUnit\Framework\Attributes\{ CoversClass, Group };

/**
 * Class UserTest
 *
 * @package MiW\Results\Tests\Entity
 */
#[Group("users")]
#[CoversClass(User::class)]
class UserTest extends \PHPUnit\Framework\TestCase
{
    private User $user;

    /**
     * Sets up the fixture.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->user = new User(
            username: 'juan',
            email: 'juan@miw.es',
            password: 'secret-password',
            enabled: true,
            isAdmin: false
        );
    }

    public function testConstructor(): void
    {
        self::assertSame('juan', $this->user->getUsername());
        self::assertSame('juan@miw.es', $this->user->getEmail());
        self::assertTrue($this->user->isEnabled());
        self::assertFalse($this->user->isAdmin());
        self::assertTrue($this->user->validatePassword('secret-password'));
    }

    public function testGetId(): void
    {
        // Valor autoincremental en BD; no podemos hacerle set por testing, así que que será 0
        self::assertSame(0, $this->user->getId());
    }

    public function testGetSetUsername(): void
    {
        $this->assertSame('juan', $this->user->getUsername());
        $this->user->setUsername('jane');
        $this->assertSame('jane', $this->user->getUsername());
    }

    public function testGetSetEmail(): void
    {
        $this->assertSame('juan@miw.es', $this->user->getEmail());
        $this->user->setEmail('jane@miw.es');
        $this->assertSame('jane@miw.es', $this->user->getEmail());
    }

    public function testIsSetEnabled(): void
    {
        $this->assertTrue($this->user->isEnabled());
        $this->user->setIsEnabled(false);
        $this->assertFalse($this->user->isEnabled());
    }

    public function testIsSetAdmin(): void
    {
        $this->assertFalse($this->user->isAdmin());
        $this->user->setIsAdmin(true);
        $this->assertTrue($this->user->isAdmin());
    }

    public function testSetValidatePassword(): void
    {
        $this->assertTrue($this->user->validatePassword('secret-password'));
        $this->user->setPassword('nueva-password');
        $this->assertTrue($this->user->validatePassword('nueva-password'));
        $this->assertFalse($this->user->validatePassword('wrong'));
    }

    public function testToString(): void
    {
        $string = (string) $this->user;
        $this->assertStringContainsString('User', $string);
        $this->assertStringContainsString('juan', $string);
        $this->assertStringContainsString('juan@miw.es', $string);
    }

    public function testJsonSerialize(): void
    {
        $json = $this->user->jsonSerialize();

        $this->assertIsArray($json);
        $this->assertArrayHasKey('id', $json);
        $this->assertSame(0, $json['id']);
        $this->assertSame('juan', $json['username']);
        $this->assertSame('juan@miw.es', $json['email']);
        $this->assertTrue($json['enabled']);
        $this->assertFalse($json['admin']);
    }
}
