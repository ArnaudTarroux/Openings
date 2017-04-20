<?php

namespace Strnoar\Openings;

use Strnoar\Openings\Exceptions\DayAlreadyExistsException;

class OpeningHours
{
    /**
     * @var array $openings
     */
    protected $openings = [];

    /**
     * OpeningHours constructor.
     * @param array $openings
     */
    public function __construct(array $openings)
    {
        $this->openings = $openings;
        $this->verifyDuplicateDays();
    }

    /**
     * @throws DayAlreadyExistsException
     */
    private function verifyDuplicateDays()
    {
        $days = [];
        foreach ($this->openings as $opening) {
            $weekday = $opening->getDay()->getWeekDay();
            if (in_array($weekday, $days)) {
                throw new DayAlreadyExistsException(
                    sprintf("Openings for %s has been declared much than once", $weekday)
                );
            }
            array_push($days, $weekday);
        }
    }

    /**
     * @return array
     */
    public function getOpenings(): array
    {
        return $this->openings;
    }

    /**
     * @param $day
     * @return bool|Opening
     */
    public function getOpeningForDay($day)
    {
        Day::checkIfIsValidDay($day);
        $opening = array_filter($this->openings, function ($opening) use ($day) {
            return $opening->getDay()->getWeekDay() == $day;
        });
        return reset($opening);
    }

    /**
     * @param $day
     * @param string $time
     * @param string $timeFormat
     * @return bool
     */
    public function isOpenForDayAt($day, $time, $timeFormat = 'H:i'): bool
    {
        Day::checkIfIsValidDay($day);
        $opening = $this->getOpeningForDay($day);
        if ($opening) {
            $time = \DateTime::createFromFormat($timeFormat, $time);
            return $this->isOpenAt($opening, $time);
        }
        return false;
    }

    /**
     * @param Opening $opening
     * @param \DateTime $time
     * @return bool
     */
    private function isOpenAt(Opening $opening, \DateTime $time): bool
    {
        $schedules = array_filter($opening->getSlots(), function ($slot) use ($time) {
            return $time >= $slot->getStart() && $time <= $slot->getEnd();
        });
        return !empty($schedules);
    }
}
