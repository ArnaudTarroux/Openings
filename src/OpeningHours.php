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
     * @var array $closed
     */
    protected $closed = [];

    /**
     * OpeningHours constructor.
     * @param array $openings
     * @param array $closed
     */
    public function __construct(array $openings, array $closed = null)
    {
        $this->openings = $openings;
        $this->verifyDuplicateDays();

        if (!is_null($closed)) {
            $this->setClosed($closed);
        }
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
        if ($this->isClosedDependant($dateTime)) {
            return false;
        }

        $opening = $this->getOpeningForDay($dateTime->format('l'));
        if ($opening) {
            return $opening->isOpenAt($dateTime);
        }
        return false;
    }

    /**
     * @param array $closedDays
     */
    private function setClosed(array $closedDays)
    {
        foreach ($closedDays as $day) {
            if (!$day instanceof Closed) {
                continue;
            }
            $this->closed[] = $day;
        }
    }

    /**
     * @return array
     */
    public function getClosed(): array
    {
        return $this->closed;
    }

    /**
     * @param \DateTime $dateTime
     * @return bool
     */
    protected function isClosedDependant(\DateTime $dateTime): bool
    {
        $hasClosedException = array_filter($this->getClosed(), function (Closed $closed) use ($dateTime) {
            return $closed->isInside($dateTime);
        });
        return count($hasClosedException) > 0;
    }
}
