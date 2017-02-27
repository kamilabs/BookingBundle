<?php

namespace Kami\BookingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Booking.
 *
 * @codeCoverageIgnore
 * @ORM\MappedSuperclass
 */
abstract class Booking
{
    protected $id;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="date")
     */
    protected $start;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="date")
     */
    protected $end;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set start.
     *
     * @param \DateTime $start
     *
     * @return Booking
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start.
     *
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end.
     *
     * @param \DateTime $end
     *
     * @return Booking
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end.
     *
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    abstract public function getItem();

    abstract public function setItem($item);
}
