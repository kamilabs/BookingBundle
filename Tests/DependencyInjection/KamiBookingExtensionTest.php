<?php

namespace Kami\BookingBundle\Tests\DependencyInjection;

use Kami\BookingBundle\DependencyInjection\KamiBookingExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @covers Kami\BookingBundle\DependencyInjection\Configuration
 * @covers Kami\BookingBundle\DependencyInjection\KamiBookingExtension
 */
class KamiBookingExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    protected $containerBuilder;

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testLoadWithoutRequiredParameter()
    {
        $loader = new KamiBookingExtension();

        $config = [];

        $loader->load($config, new ContainerBuilder());
    }

    public function testLoadWithRequiredParameters()
    {
        $loader = new KamiBookingExtension();
        $config = ['kami_booking' => [
            'entity_class'=> 'Vendor\Bundle\Entity\Class', ],
        ];

        $loader->load($config, new ContainerBuilder());
    }

    public function testContainerHasNeccessaryServices()
    {
        $this->loadConfiguration();

        $entityClass = $this->containerBuilder->getParameter('kami_booking.entity_class');
        $services = $this->containerBuilder->getServiceIds();

        $this->assertEquals('Vendor\Bundle\Entity\Class', $entityClass);
        $this->assertContains('booker', $services);
        $this->assertContains('booking_calendar', $services);
    }

    protected function loadConfiguration()
    {
        $this->containerBuilder = new ContainerBuilder();
        $loader = new KamiBookingExtension();
        $config = ['kami_booking' => [
            'entity_class'=> 'Vendor\Bundle\Entity\Class', ],
        ];

        $loader->load($config, $this->containerBuilder);

        $this->assertInstanceOf('Symfony\Component\DependencyInjection\ContainerBuilder', $this->containerBuilder);
    }
}
