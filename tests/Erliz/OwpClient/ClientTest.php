<?php

/**
 * OwpClient package
 *
 * @package   Erliz\OwpClient
 * @author    Stanislav Vetlovskiy <mrerliz@gmail.com>
 * @copyright Copyright (c) Stanislav Vetlovskiy
 * @license   MIT
 */

namespace Erliz\OwpClient;

use Erliz\OwpClient\Entity\HardwareServerEntity;
use Erliz\OwpClient\Entity\VirtualServerEntity;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;

/**
 * Class ClientTest
 *
 * @author Stanislav Vetlovskiy <mrerliz@gmail.com>
 */
class ClientTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return Client|PHPUnit_Framework_MockObject_MockObject
     */
    public function createClient()
    {
        $owp = $this->getMockBuilder('Erliz\OwpClient\Client')
            ->setConstructorArgs(
                [
                    'localhost',
                    'user',
                    'password',
                    '443',
                ]
            )
            ->setMethods(['makeRequest'])
            ->getMock();

        return $owp;
    }

    /**
     * Test getHardwareServers
     */
    public function testGetHardwareServers()
    {
        $owp = $this->createClient();
        $owp->expects($this->once())
            ->method('makeRequest')
            ->with($this->equalTo('hardware_servers/list'))
            ->will($this->returnValue(simplexml_load_file(__DIR__.'/Fixture/hardware_servers__list.xml')));

        $result = $owp->getHardwareServers();
        $this->assertTrue(is_array($result));
        $this->assertNotEmpty($result);
        /** @var HardwareServerEntity $server */
        $server = $result[0];
        $this->assertInstanceOf('Erliz\OwpClient\Entity\HardwareServerEntity', $server);
        $this->assertNotEmpty((string) $server);
        $this->assertNotEmpty($server->getHost());
        $this->assertNotEmpty($server->getId());
    }

    /**
     * Test getHardwareServers
     */
    public function testGetHardwareServersWithVirtualServers()
    {
        $owp = $this->createClient();
        $owp->method('makeRequest')
            ->will(
                $this->returnValueMap([
                    [
                        'hardware_servers/list',
                        simplexml_load_file(__DIR__.'/Fixture/hardware_servers__list__one.xml'),
                    ],
                    [
                        'hardware_servers/virtual_servers?id=4',
                        simplexml_load_file(__DIR__.'/Fixture/hardware_servers__virtual_servers__id_4.xml'),
                    ],
                ])
            );

        $servers = $owp->getHardwareServers(true);
        $this->assertTrue(is_array($servers));
        $this->assertCount(1, $servers);
        /** @var HardwareServerEntity $server */
        $server = $servers[0];
        $this->assertNotEmpty($server->getVirtualServers());
        $this->assertInstanceOf('Erliz\OwpClient\Entity\VirtualServerEntity', $server->getVirtualServers()[0]);
    }

    /**
     * Test getVirtualServersByHardwareServerId
     */
    public function testGetVirtualServersByHardwareServerId()
    {
        $owp = $this->createClient();
        $owp->expects($this->once())
            ->method('makeRequest')
            ->with($this->equalTo('hardware_servers/virtual_servers?id=4'))
            ->will(
                $this->returnValue(
                    simplexml_load_file(__DIR__.'/Fixture/hardware_servers__virtual_servers__id_4.xml')
                )
            );

        $result = $owp->getVirtualServersByHardwareServerId(4);
        $this->assertTrue(is_array($result));
        $this->assertNotEmpty($result);
        /** @var VirtualServerEntity $server */
        $server = $result[0];
        $this->assertInstanceOf('Erliz\OwpClient\Entity\VirtualServerEntity', $server);
        $this->assertNotEmpty((string) $server);
        $this->assertNotEmpty($server->getHostName());
        $this->assertNotEmpty($server->getId());
    }

    /**
     *  Test getAllVirtualServers
     */
    public function testGetAllVirtualServers()
    {
        $owpClient = $this->getMockClient();

        $result = $owpClient->getAllVirtualServers();

        $this->assertNotEmpty($result);
        $this->assertCount(7, $result);
        $this->assertInstanceOf('Erliz\OwpClient\Entity\VirtualServerEntity', $result[0]);
    }

    /**
     * @return Client|PHPUnit_Framework_MockObject_MockObject
     */
    public function getMockClient()
    {
        $client = $this->createClient();

        $client
            ->method('makeRequest')
            ->will(
                $this->returnValueMap(
                    [
                        [
                            'hardware_servers/list',
                            simplexml_load_file(__DIR__.'/Fixture/hardware_servers__list__one.xml'),
                        ],
                        [
                            'hardware_servers/virtual_servers?id=4',
                            simplexml_load_file(__DIR__.'/Fixture/hardware_servers__virtual_servers__id_4.xml'),
                        ],
                    ]
                )
            );

        return $client;
    }
}
