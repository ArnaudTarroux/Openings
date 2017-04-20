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
        $opening = array_filter($this->openings, function (Opening $opening) use ($day) {
            return $opening->getDay()->getWeekDay() == strtolower($day);
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
        $opening = $this->getOpeningForDay($day);
        if ($opening) {
            $time = \DateTime::createFromFormat($timeFormat, $time);
            return $opening->isOpenAt($time);
        }
        return false;
    }

    /**
     * @param \DateTime $dateTime
     * @return bool
     */
    public function isOpenOnDatetime(\DateTime $dateTime): bool
    {
        $opening = $this->getOpeningForDay($dateTime->format('l'));
        if ($opening) {
            return $opening->isOpenAt($dateTime);
        }
        return false;
    }
}
