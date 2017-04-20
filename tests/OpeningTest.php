<?php

namespace Strnoar\Openings\Test;

use PHPUnit\Framework\TestCase;
use Strnoar\Openings\Day;
use Strnoar\Openings\Exceptions\DayDoesNotExistsException;
use Strnoar\Openings\Opening;

class OpeningTest extends TestCase
{
    /**
     * @var Opening
     */
    protected $opening;
    protected $day = Day::MONDAY;
    protected $schedules = [['09:00', '10:00'], ['12:00', '16:30']];

    public function setUp()
    {
        $this->opening = new Opening($this->day, $this->schedules);
    }

    public function testCreateWithInvalidDayName()
    {
        $this->expectException(DayDoesNotExistsException::class);
        $opening = new Opening('funday', [['09:00', '10:00']]);
    }

    public function testDayIsCorrect()
    {
        $this->assertEquals($this->day, $this->opening->getDay()->getWeekDay());
    }

    public function testSlotCreatingOpening()
    {
        $this->assertCount(count($this->schedules), $this->opening->getSlots());
    }
}
