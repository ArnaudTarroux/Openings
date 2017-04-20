<?php

namespace Strnoar\Openings\Test;

use PHPUnit\Framework\TestCase;
use Strnoar\Openings\Closed;

class ClosedTest extends TestCase
{
    /**
     * @var Closed $closed
     */
    protected $closed;

    public function setUp()
    {
        $start = new \DateTime('2017-04-19 09:00');
        $end = new \DateTime('2017-04-21 19:00');
        $this->closed = new Closed($start, $end);
    }

    public function testIfInside()
    {
        $date = new \DateTime('2017-04-20 10:00');
        $isClosed = $this->closed->isInside($date);
        $this->assertTrue($isClosed);
    }

    public function testIfNotInside()
    {
        $date = new \DateTime('2017-04-23 10:00');
        $isClosed = $this->closed->isInside($date);
        $this->assertFalse($isClosed);
    }
}