<?php

namespace Kami\BookingBundle\Tests\Helper;

use Kami\BookingBundle\Helper\Booker;
use Kami\BookingBundle\Tests\Fixtures\ORM\Entity\Booking;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookerTest extends WebTestCase
{
    protected $container;

    protected $item;

    public function setUp()
    {
        if (!class_exists('Doctrine\ORM\EntityManager')) {
            $this->markTestSkipped('Doctrine ORM is not available.');
        }

        parent::setUp();
        self::createClient();

        $this->container = self::$kernel->getContainer();
        $this->item = $this->container->get('doctrine')
            ->getRepository('Kami\BookingBundle\Tests\Fixtures\ORM\Entity\BookableItem')
            ->findOneBy([]);
    }

    public function testShouldBeConstructedWithNeededArguments()
    {
        new Booker('Kami\BookingBundle\Tests\Fixtures\ORM\Entity\Booking',
            $this->container->get('doctrine'));
    }

    public function testKernelShouldContainBookerService()
    {
        $this->assertInstanceOf('Symfony\Component\DependencyInjection\ContainerInterface', $this->container);
        $this->assertInstanceOf('Kami\BookingBundle\Helper\Booker', $this->container->get('booker'));
    }

    /**
     * @dataProvider multipleDatesProvider
     */
    public function testIsAvailableForPeriod($start, $end, $expected)
    {
        $booker = $this->container->get('booker');

        $this->assertEquals($expected, $booker->isAvailableForPeriod(
            $this->item,
            new \DateTime($start),
            new \DateTime($end))
        );
    }

    /**
     * @dataProvider singleDateProvider
     */
    public function testIsAvailableForDate($date, $expected)
    {
        $booker = $this->container->get('booker');

        $this->assertEquals($expected, $booker->isAvailableForDate(
                $this->item,
                new \DateTime($date)
        ));
    }

    /**
     * @dataProvider multipleDatesProvider
     */
    public function testWhereAvailableForPeriod($start, $end)
    {
        $booker = $this->container->get('booker');

        $qb = $this->container->get('doctrine')
            ->getRepository('Kami\BookingBundle\Tests\Fixtures\ORM\Entity\BookableItem')
            ->createQueryBuilder('i');

        $booker->whereAvailableForPeriod($qb, ['field'=>'bookings', 'alias'=>'b'],
            new\DateTime($start), new \DateTime($end));

        $this->assertInstanceOf('Doctrine\ORM\QueryBuilder', $qb);
        $dql = $qb->getDql();

        $this->assertEquals('SELECT i FROM Kami\BookingBundle\Tests\Fixtures\ORM\Entity\BookableItem i LEFT JOIN bookings b WHERE (b.start >= :start AND b.end <= :end) OR (b.start <= :start AND b.end >= :end) OR (b.start <= :start AND b.end >= :end AND b.start <= :end) OR (b.start >= :start AND b.end <= :end AND b.end >= :start)', $dql);
    }

    /**
     * @dataProvider singleDateProvider
     */
    public function testWhereIsAvailableForSingleDate($start)
    {
        $booker = $this->container->get('booker');

        $qb = $this->container->get('doctrine')
            ->getRepository('Kami\BookingBundle\Tests\Fixtures\ORM\Entity\BookableItem')
            ->createQueryBuilder('i');

        $booker->whereAvailableForDate($qb, ['field'=>'bookings', 'alias'=>'b'],
            new\DateTime($start));

        $this->assertInstanceOf('Doctrine\ORM\QueryBuilder', $qb);
        $dql = $qb->getDql();

        $this->assertEquals('SELECT i FROM Kami\BookingBundle\Tests\Fixtures\ORM\Entity\BookableItem i LEFT JOIN bookings b WHERE b.start >= :date AND b.end <= :date', $dql);
    }

    /**
     * @dataProvider multipleDatesProvider
     */
    public function testShouldBookIfAvailable($start, $end, $expected)
    {
        $booker = $this->container->get('booker');

        $result = $booker->book($this->item, new \DateTime($start),
            new \DateTime($end));

        $this->assertEquals($expected, $result instanceof Booking);

        if ($result instanceof Booking) {
            $manager = $this->container->get('doctrine')->getManager();
            $manager->remove($result);
            $manager->flush();
        }
    }

    public function multipleDatesProvider()
    {
        return [
            ['2014-05-03', '2014-05-06', false],
            ['2014-04-20', '2014-05-02', false],
            ['2014-05-07', '2014-05-15', false],
            ['2014-04-03', '2014-04-06', true],
            ['2014-05-10', '2014-05-20', true],
        ];
    }

    public function singleDateProvider()
    {
        return [
            ['2014-05-03', false],
            ['2014-04-20', true],
            ['2014-05-10', true],
        ];
    }
}
