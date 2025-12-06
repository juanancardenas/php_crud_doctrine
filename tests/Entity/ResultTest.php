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
        $this->assertSame(self::POINTS, $this->result->getResult());
        $this->assertSame($this->user, $this->result->getUser());
        $this->assertSame($this->time, $this->result->getTime());
        $this->assertSame(0, $this->result->getId());
    }

    /**
     * Implement testGet_Id().
     *
     * @return void
     */
    public function testGetId():void
    {
        // Valor autoincremental en BD; no podemos hacerle set por testing, así que que será 0
        $this->assertSame(0, $this->result->getId());
    }

    /**
     * Implement testUsername().
     *
     * @return void
     */
    public function testResult(): void
    {
        $this->assertSame(self::POINTS, $this->result->getResult());
        $this->result->setResult(3204);
        $this->assertSame(3204, $this->result->getResult());
    }

    /**
     * Implement testUser().
     *
     * @return void
     */
    public function testUser(): void
    {
        $this->assertSame($this->user, $this->result->getUser());

        $newUser = new User('juan', 'juan@miw.es', 'secret-password', true, false);
        $this->result->setUserId($newUser);

        $this->assertSame($newUser, $this->result->getUser());
    }

    /**
     * Implement testTime().
     *
     * @return void
     */
    public function testTime(): void
    {
        $this->assertSame($this->time, $this->result->getTime());

        $newTime = new \DateTime('-1 day');
        $this->result->setTime($newTime);

        $this->assertSame($newTime, $this->result->getTime());
    }

    /**
     * Implement testTo_String().
     *
     * @return void
     */
    public function testToString(): void
    {
        $text = (string) $this->result;

        $this->assertStringContainsString((string) self::POINTS, $text);
        $this->assertStringContainsString(self::USERNAME, $text);
        $this->assertStringContainsString($this->time->format('Y-m-d'), $text);
    }

    /**
     * Implement testJson_Serialize().
     *
     * @return void
     */
    public function testJsonSerialize(): void
    {
        $json = $this->result->jsonSerialize();

        $this->assertIsArray($json);
        $this->assertSame(0, $json['id']);
        $this->assertSame(self::POINTS, $json['result']);
        $this->assertSame($this->user, $json['user']);
        $this->assertSame(
            $this->time->format('Y-m-d H:i:s'),
            $json['time']
        );
    }
}
