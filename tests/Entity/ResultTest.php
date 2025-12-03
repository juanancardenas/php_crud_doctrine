<?php

/**
 * tests/Entity/ResultTest.php
 *
 * @category EntityTests
 * @package  MiW\Results\Tests
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

namespace MiW\Results\Tests\Entity;

use PHPUnit\Framework\Attributes\{ CoversClass, Group };
use MiW\Results\Entity\{ Result, User };

/**
 * Class ResultTest
 *
 * @package MiW\Results\Tests\Entity
 */
#[Group("results")]
#[CoversClass(Result::class)]
class ResultTest extends \PHPUnit\Framework\TestCase
{
    private User $user;

    private Result $result;

    private const USERNAME = 'uSeR ñ¿?Ñ';
    private const POINTS = 2025;

    private \DateTime $time;

    /**
     * Sets up the fixture.
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->user = new User();
        $this->user->setUsername(self::USERNAME);
        $this->time = new \DateTime('now');
        $this->result = new Result(
            self::POINTS,
            $this->user,
            $this->time
        );
    }

    /**
     * Implement testConstructor
     *
     * @return void
     */
    public function testConstructor(): void
    {
        self::markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * Implement testGet_Id().
     *
     * @return void
     */
    public function testGetId():void
    {
        self::markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * Implement testUsername().
     *
     * @return void
     */
    public function testResult(): void
    {
        self::markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * Implement testUser().
     *
     * @return void
     */
    public function testUser(): void
    {
        self::markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * Implement testTime().
     *
     * @return void
     */
    public function testTime(): void
    {
        self::markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * Implement testTo_String().
     *
     * @return void
     */
    public function testToString(): void
    {
        self::markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * Implement testJson_Serialize().
     *
     * @return void
     */
    public function testJsonSerialize(): void
    {
        self::markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
