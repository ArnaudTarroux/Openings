<?php

namespace Strnoar\Openings;

use Strnoar\Openings\Exceptions\InvalidClosedFormatException;

class Closed
{
    /**
     * @var \DateTime
     */
    private $closedStart;
    /**
     * @var \DateTime
     */
    private $closedEnd;

    /**
     * Closed constructor.
     * @param \DateTime $closedStart
     * @param \DateTime $closedEnd
     */
    public function __construct(\DateTime $closedStart, \DateTime $closedEnd)
    {
        $this->closedStart = $closedStart;
        $this->closedEnd = $closedEnd;
    }

    /**
     * @return \DateTime
     */
    public function getClosedEnd(): \DateTime
    {
        return $this->closedEnd;
    }

    /**
     * @return \DateTime
     */
    public function getClosedStart(): \DateTime
    {
        return $this->closedStart;
    }

    /**
     * @param \DateTime $dateTime
     * @return bool
     */
    public function isInside(\DateTime $dateTime): bool
    {
        return $dateTime >= $this->getClosedStart() && $dateTime <= $this->getClosedEnd();
    }
}
