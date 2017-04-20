<?php

namespace Strnoar\Openings;

class Schedule
{
    /**
     * @var \DateTime
     */
    private $start;
    /**
     * @var \DateTime
     */
    private $end;

    /**
     * Schedule constructor.
     * @param \DateTime $start
     * @param \DateTime $end
     */
    public function __construct(\DateTime $start, \DateTime $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @return \DateTime
     */
    public function getEnd(): \DateTime
    {
        return $this->end;
    }

    /**
     * @return \DateTime
     */
    public function getStart(): \DateTime
    {
        return $this->start;
    }
}
