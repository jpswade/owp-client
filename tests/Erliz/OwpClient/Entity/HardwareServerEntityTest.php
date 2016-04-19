<?php

/**
 * OwpClient package
 *
 * @package   Erliz\OwpClient
 * @author    Stanislav Vetlovskiy <mrerliz@gmail.com>
 * @copyright Copyright (c) Stanislav Vetlovskiy
 * @license   MIT
 */

namespace Erliz\OwpClient\Entity;

use PHPUnit_Framework_TestCase;

/**
 * Class HardwareServerEntityTest
 *
 * @author Stanislav Vetlovskiy <mrerliz@gmail.com>
 */
class HardwareServerEntityTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function entityProvider()
    {
        return [
            [
                [
                    'daemonPort' => 7767,
                    'defaultOsTemplate' => 'linux-x86_64',
                    'defaultServerTemplate' => 'vswap-2g',
                    'description' => '32G / SAS 1.1T',
                    'host' => 'dev1.example.com',
                    'id' => 4,
                    'useSsl' => true,
                    'vswap' => true,
                ],
                [
                    'daemonPort' => '7767',
                    'defaultOsTemplate' => '',
                    'defaultServerTemplate' => 'vswap-2g',
                    'description' => null,
                    'host' => 'localhost',
                    'id' => '4',
                    'useSsl' => false,
                    'vswap' => false,
                ],
            ],
        ];
    }

    /**
     * @dataProvider entityProvider
     *
     * @param array $entityData
     */
    public function testSettersAndGetters(array $entityData)
    {
        $hardwareServer = new HardwareServerEntity();
        $hardwareServer
            ->setDaemonPort($entityData['daemonPort'])
            ->setDefaultOSTemplate($entityData['defaultOsTemplate'])
            ->setDefaultServerTemplate($entityData['defaultServerTemplate'])
            ->setDescription($entityData['description'])
            ->setHost($entityData['host'])
            ->setId($entityData['id'])
            ->setUseSSL($entityData['useSsl'])
            ->setVSwap($entityData['vswap']);

        $this->assertTrue(is_int($hardwareServer->getDaemonPort()));
        $this->assertEquals($entityData['daemonPort'], $hardwareServer->getDaemonPort());

        $this->assertTrue(is_string($hardwareServer->getDefaultOSTemplate()));
        $this->assertEquals($entityData['defaultOsTemplate'], $hardwareServer->getDefaultOSTemplate());

        $this->assertTrue(is_string($hardwareServer->getDefaultServerTemplate()));
        $this->assertEquals($entityData['defaultServerTemplate'], $hardwareServer->getDefaultServerTemplate());

        $this->assertTrue(is_string($hardwareServer->getDescription()));
        $this->assertEquals($entityData['description'], $hardwareServer->getDescription());

        $this->assertTrue(is_string($hardwareServer->getHost()));
        $this->assertEquals($entityData['host'], $hardwareServer->getHost());

        $this->assertTrue(is_int($hardwareServer->getId()));
        $this->assertEquals($entityData['id'], $hardwareServer->getId());

        $this->assertTrue(is_bool($hardwareServer->isUseSSL()));
        $this->assertEquals($entityData['useSsl'], $hardwareServer->isUseSSL());

        $this->assertTrue(is_bool($hardwareServer->isVSwap()));
        $this->assertEquals($entityData['vswap'], $hardwareServer->isVSwap());
    }

    /**
     * test virtual servers property
     */
    public function testVirtualServersProp()
    {
        $hardwareServer = new HardwareServerEntity();

        $this->assertTrue(is_array($hardwareServer->getVirtualServers()));
        $this->assertCount(0, $hardwareServer->getVirtualServers());

        $hardwareServer->addVirtualServers(new VirtualServerEntity());
        $this->assertCount(1, $hardwareServer->getVirtualServers());

        $hardwareServer->addVirtualServers(new VirtualServerEntity());
        $hardwareServer->addVirtualServers(new VirtualServerEntity());
        // 1 cause virtualServers with the same hostname
        $this->assertCount(1, $hardwareServer->getVirtualServers());

        $virtualServer = new VirtualServerEntity();
        $virtualServer->setHostName('localhost');
        $hardwareServer->addVirtualServers($virtualServer);
        $this->assertCount(2, $hardwareServer->getVirtualServers());

        $hardwareServer->setVirtualServers([]);
        $this->assertCount(0, $hardwareServer->getVirtualServers());

        $hardwareServer->setVirtualServers([
            new VirtualServerEntity(),
            $virtualServer,
        ]);
        $this->assertCount(2, $hardwareServer->getVirtualServers());
    }

    /**
     * @dataProvider entityProvider
     *
     * @param array $entityData
     */
    public function testToArray(array $entityData)
    {
        $hardwareServer = new HardwareServerEntity();
        $hardwareServer
            ->setDaemonPort($entityData['daemonPort'])
            ->setDefaultOSTemplate($entityData['defaultOsTemplate'])
            ->setDefaultServerTemplate($entityData['defaultServerTemplate'])
            ->setDescription($entityData['description'])
            ->setHost($entityData['host'])
            ->setId($entityData['id'])
            ->setUseSSL($entityData['useSsl'])
            ->setVSwap($entityData['vswap']);

        $virtualServer = new VirtualServerEntity();
        $virtualServer->setHostName('localhost');

        $hardwareServer->setVirtualServers([
            $virtualServer,
            new VirtualServerEntity(),
        ]);

        $arrayData = $hardwareServer->__toArray();

        $this->assertTrue(is_array($arrayData));
        $this->assertCount(9, $arrayData);
        $this->assertEquals($entityData['host'], $arrayData['host']);
        $this->assertCount(2, $arrayData['virtualServers']);
        $this->assertEquals('localhost', $arrayData['virtualServers'][0]['hostName']);
    }

    /**
     * @dataProvider entityProvider
     *
     * @param array $entityData
     */
    public function testJsonSerialize(array $entityData)
    {
        $hardwareServer = new HardwareServerEntity();
        $hardwareServer
            ->setDaemonPort($entityData['daemonPort'])
            ->setDefaultOSTemplate($entityData['defaultOsTemplate'])
            ->setDefaultServerTemplate($entityData['defaultServerTemplate'])
            ->setDescription($entityData['description'])
            ->setHost($entityData['host'])
            ->setId($entityData['id'])
            ->setUseSSL($entityData['useSsl'])
            ->setVSwap($entityData['vswap']);

        $this->assertTrue(is_array($hardwareServer->jsonSerialize()));
        $this->assertNotEmpty($hardwareServer->jsonSerialize());

        $serverJson = json_encode($hardwareServer);
        $this->assertEmpty(json_last_error());
        $this->assertTrue(is_string($serverJson));
        $this->assertNotEmpty($serverJson);
        $this->assertContains('virtualServers', $serverJson);
    }
}
