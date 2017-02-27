<?php

namespace Kami\BookingBundle\Tests\Fixtures\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Kami\BookingBundle\Tests\Fixtures\ORM\Entity\Booking;

class LoadBookingData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $booking = new Booking();
        $item = $manager->getRepository('Kami\BookingBundle\Tests\Fixtures\ORM\Entity\BookableItem')
            ->findOneBy([]);

        $booking->setItem($item);
        $booking->setStart(new \DateTime('2014-05-01'));
        $booking->setEnd(new \DateTime('2014-05-09'));

        $manager->persist($booking);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
