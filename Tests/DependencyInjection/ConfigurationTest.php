<?php

namespace Kami\BookingBundle\Tests\DependencyInjection;

use Kami\BookingBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testImplementsConfigurationInterface()
    {
        $rc = new \ReflectionClass('Kami\BookingBundle\DependencyInjection\Configuration');

        $this->assertTrue($rc->implementsInterface('Symfony\Component\Config\Definition\ConfigurationInterface'));
    }

    public function testCouldBeConstructedWithResolversAndLoadersFactoriesAsArguments()
    {
        new Configuration([], []);
    }

    public function testInjection()
    {
        $config = $this->processConfiguration(new Configuration(),
            [
                'kami_booking'=> [
                    'entity_class' => 'Vendor\Bundle\Entity\Class',
                ],
            ]
        );

        $this->assertArrayHasKey('entity_class', $config);
        $this->assertEquals($config['entity_class'], 'Vendor\Bundle\Entity\Class');
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testExceptionIfRequiredParameterIsMissing()
    {
        $config = $this->processConfiguration(new Configuration(),
            [
                'kami_booking'=> [
                ],
            ]
        );
    }

    /**
     * @param ConfigurationInterface $configuration
     * @param array                  $configs
     *
     * @return array
     */
    protected function processConfiguration(ConfigurationInterface $configuration, array $configs)
    {
        $processor = new Processor();

        return $processor->processConfiguration($configuration, $configs);
    }
}
