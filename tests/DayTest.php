<?php

namespace Strnoar\Openings\Test;

use PHPUnit\Framework\TestCase;
use Strnoar\Openings\Day;
use Strnoar\Openings\Exceptions\DayDoesNotExistsException;

class DayTest extends TestCase
{
    const INVALID_DAY_NAME = 'sundy';

    public function testInvalidDayName()
    {
        $this->assertFalse(Day::exists(self::INVALID_DAY_NAME));
    }

    public function testValidDayName()
    {
        $this->assertTrue(Day::exists(Day::MONDAY));
    }

    public function testCreateDayWithInvalidName()
    {
        $this->expectException(DayDoesNotExistsException::class);
        $day = new Day(self::INVALID_DAY_NAME);
    }

    public function testCreateDayWithValidName()
    {
        $day = new Day(Day::MONDAY);
        $this->assertInstanceOf(Day::class, $day);
        $this->assertSame(Day::MONDAY, $day->getWeekDay());
    }
}