<?php

namespace Strnoar\Openings;

use Strnoar\Openings\Exceptions\InvalidSlotException;

class Opening
{
    /**
     * @var string $day
     */
    protected $day;
    /**
     * @var array $slots
     */
    protected $slots = [];
    /**
     * @var string
     */
    private $dateFormat;

    /**
     * Opening constructor.
     * @param string $day
     * @param array $slots
     * @param string $dateFormat The date format for create Datetime instance
     */
    public function __construct($day, array $slots, $dateFormat = 'H:i')
    {
        $this->day = new Day($day);
        $this->dateFormat = $dateFormat;
        $this->initSlots($slots);
    }

    /**
     * @param array $slots
     * @throws InvalidSlotException
     */
    private function initSlots(array $slots)
    {
        foreach ($slots as $slot) {
            // If the slot not contains array
            if (!is_array($slot)) {
                throw new InvalidSlotException("Child slot must be an array");
            }
            // If slot not contains start an end
            if (count($slot) != 2) {
                throw new InvalidSlotException("Child slot must contains 2 values (start, end)");
            }
            $this->addSlot($slot);
        }
    }

    /**
     * @param array $slot
     */
    private function addSlot(array $slot)
    {
        $start = \DateTime::createFromFormat($this->dateFormat, $slot[0]);
        $end = \DateTime::createFromFormat($this->dateFormat, $slot[1]);
        $this->slots[] = new Schedule($start, $end);
    }

    /**
     * @return Day
     */
    public function getDay(): Day
    {
        return $this->day;
    }

    /**
     * @return array
     */
    public function getSlots(): array
    {
        return $this->slots;
    }

    /**
     * @param \DateTime $time
     * @return bool
     */
    public function isOpenAt(\DateTime $time): bool
    {
        $schedules = array_filter($this->getSlots(), function ($slot) use ($time) {
            return $time >= $slot->getStart() && $time <= $slot->getEnd();
        });
        return !empty($schedules);
    }
}
