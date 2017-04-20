<?php

namespace Strnoar\Openings\Test;

use PHPUnit\Framework\TestCase;
use Strnoar\Openings\Day;
use Strnoar\Openings\Exceptions\DayAlreadyExistsException;
use Strnoar\Openings\Opening;
use Strnoar\Openings\OpeningHours;

class OpeningHoursTest extends TestCase
{
    const MONDAY_HOURS = [['09:00', '11:00'], ['12:30', '17:45']];
    const FRIDAY_HOURS = [['07:00', '14:00'], ['15:30', '19:10']];

    /**
     * @var OpeningHours $openingHours
     */
    protected $openingHours;

    public function setUp()
    {
        $this->openingHours = new OpeningHours([
            new Opening(Day::MONDAY, self::MONDAY_HOURS),
            new Opening(Day::FRIDAY, self::FRIDAY_HOURS)
        ]);
    }

    public function testDeclareSameDayMuchThanOnce()
    {
        $this->expectException(DayAlreadyExistsException::class);
        new OpeningHours([
            new Opening(Day::MONDAY, self::MONDAY_HOURS),
            new Opening(Day::MONDAY, self::MONDAY_HOURS)
        ]);
    }

    public function testCreateValidOpenings()
    {
        $this->assertInstanceOf(OpeningHours::class, $this->openingHours);
        $this->assertCount(2, $this->openingHours->getOpenings());
    }

    public function testOpeningsAreValid()
    {
        foreach ($this->openingHours->getOpenings() as $openingHour) {
            $this->assertInstanceOf(Opening::class, $openingHour);
            $this->assertInstanceOf(Day::class, $openingHour->getDay());
        }
    }

    public function testGetOpeningForDay()
    {
        $openingForFriday = $this->openingHours->getOpeningForDay(Day::FRIDAY);
        $this->assertInstanceOf(Opening::class, $openingForFriday);
        $this->assertEquals(Day::FRIDAY, $openingForFriday->getDay()->getWeekDay());
        $this->assertCount(count(self::FRIDAY_HOURS), $openingForFriday->getSlots());
    }

    public function testGetOpeningForBadDay()
    {
        $openingForFriday = $this->openingHours->getOpeningForDay(Day::SATURDAY);
        $this->assertFalse($openingForFriday);
    }

    public function testCheckIsOpen()
    {
        $validOpened = $this->openingHours->isOpenForDayAt(Day::FRIDAY, '09:00');
        $invalidOpened = $this->openingHours->isOpenForDayAt(Day::SATURDAY, '09:00');
        $this->assertTrue($validOpened);
        $this->assertFalse($invalidOpened);
    }

    public function testCheckIsOpenOnDatetime()
    {
        $opened = $this->openingHours->isOpenOnDatetime(new \DateTime('2017-04-21 09:00'));
        $notOpened = $this->openingHours->isOpenOnDatetime(new \DateTime('2017-04-20 09:00'));
        $this->assertTrue($opened);
        $this->assertFalse($notOpened);
    }
}
