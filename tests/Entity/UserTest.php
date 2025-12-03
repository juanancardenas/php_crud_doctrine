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
        $this->user = new User();
    }

    public function testConstructor(): void
    {
        self::markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testGetId(): void
    {
        self::markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testGetSetUsername(): void
    {
        self::markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testGetSetEmail(): void
    {
        self::markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testIsSetEnabled(): void
    {
        self::markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testIsSetAdmin(): void
    {
        self::markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testSetValidatePassword(): void
    {
        self::markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testToString(): void
    {
        self::markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testJsonSerialize(): void
    {
        self::markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
