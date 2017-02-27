<?php

namespace Kami\BookingBundle\Tests\Twig;

use Kami\BookingBundle\Twig\CalendarExtension;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CalendarExtensionTest extends WebTestCase
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

    public function testCanBeConstructedWithNeededArguments()
    {
        new CalendarExtension('Kami\BookingBundle\Tests\Fixtures\ORM\Entity\Booking',
            $this->container->get('doctrine'));
    }

    public function testContainerShouldContainCalendarExtension()
    {
        $this->assertInstanceOf('Symfony\Component\DependencyInjection\ContainerInterface', $this->container);
        $this->assertInstanceOf('Kami\BookingBundle\Twig\CalendarExtension',
            $this->container->get('booking_calendar'));
    }

    public function testShouldBeTwigExtension()
    {
        $this->assertInstanceOf('\Twig_Extension', $this->container->get('booking_calendar'));
    }

    public function testShouldBeNamed()
    {
        $this->assertEquals('kami_booking_bundle_calendar', $this->container->get('booking_calendar')->getName());
    }

    public function testShouldContainFunctions()
    {
        $cal = $this->container->get('booking_calendar');

        $functions = $cal->getFunctions();

        $this->assertTrue(count($functions) > 0);
    }
}
