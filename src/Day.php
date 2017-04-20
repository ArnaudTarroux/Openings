<?php

namespace Strnoar\Openings;

use Strnoar\Openings\Exceptions\DayDoesNotExistsException;

class Day
{
    const MONDAY = 'monday';
    const TUESDAY = 'tuesday';
    const WEDNESDAY = 'wednesday';
    const THURSDAY = 'thursday';
    const FRIDAY = 'friday';
    const SATURDAY = 'saturday';
    const SUNDAY = 'sunday';

    /**
     * @var string $day
     */
    protected $weekday;

    /**
     * Day constructor.
     * @param $day
     * @throws DayDoesNotExistsException
     */
    public function __construct($day)
    {
        self::checkIfIsValidDay($day);
        $this->weekday = $day;
    }

    /**
     * Return all days as array
     * @return array
     */
    public static function listDays(): array
    {
        return [
            static::MONDAY,
            static::TUESDAY,
            static::WEDNESDAY,
            static::THURSDAY,
            static::FRIDAY,
            static::SATURDAY,
            static::SUNDAY,
        ];
    }

    /**
     * Return if a day exists
     * @param string $day
     * @return bool
     */
    public static function exists(string $day): bool
    {
        return in_array(strtolower($day), self::listDays());
    }

    /**
     * @param string $day
     * @throws DayDoesNotExistsException
     */
    public static function checkIfIsValidDay(string $day)
    {
        $day = strtolower($day);
        if (!self::exists($day)) {
            throw new DayDoesNotExistsException(sprintf("%s is not a valid day", $day));
        }
    }

    /**
     * @return string
     */
    public function getWeekDay(): string
    {
        return $this->weekday;
    }
}
