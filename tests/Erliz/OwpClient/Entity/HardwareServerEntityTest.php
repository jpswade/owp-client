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
        $entity = new HardwareServerEntity();

        $this->assertSame($entity, $entity->setDaemonPort($entityData['daemonPort']));
        $this->assertTrue(is_int($entity->getDaemonPort()));
        $this->assertEquals($entityData['daemonPort'], $entity->getDaemonPort());

        $this->assertSame($entity, $entity->setDefaultOSTemplate($entityData['defaultOsTemplate']));
        $this->assertTrue(is_string($entity->getDefaultOSTemplate()));
        $this->assertEquals($entityData['defaultOsTemplate'], $entity->getDefaultOSTemplate());

        $this->assertSame($entity, $entity->setDefaultServerTemplate($entityData['defaultServerTemplate']));
        $this->assertTrue(is_string($entity->getDefaultServerTemplate()));
        $this->assertEquals($entityData['defaultServerTemplate'], $entity->getDefaultServerTemplate());

        $this->assertSame($entity, $entity->setDescription($entityData['description']));
        $this->assertTrue(is_string($entity->getDescription()));
        $this->assertEquals($entityData['description'], $entity->getDescription());

        $this->assertSame($entity, $entity->setHost($entityData['host']));
        $this->assertTrue(is_string($entity->getHost()));
        $this->assertEquals($entityData['host'], $entity->getHost());

        $this->assertSame($entity, $entity->setId($entityData['id']));
        $this->assertTrue(is_int($entity->getId()));
        $this->assertEquals($entityData['id'], $entity->getId());

        $this->assertSame($entity, $entity->setUseSSL($entityData['useSsl']));
        $this->assertTrue(is_bool($entity->isUseSSL()));
        $this->assertEquals($entityData['useSsl'], $entity->isUseSSL());

        $this->assertSame($entity, $entity->setVSwap($entityData['vswap']));
        $this->assertTrue(is_bool($entity->isVSwap()));
        $this->assertEquals($entityData['vswap'], $entity->isVSwap());
    }

    /**
     * Test hardware stats property
     */
    public function testStatsProp()
    {
        $entity = new HardwareServerEntity();

        $stats = new HardwareServerStatsEntity();
        $stats->setOSVersion('test_OS');

        $this->assertSame($entity, $entity->setStats($stats));
        $this->assertInstanceOf(HardwareServerStatsEntity::class, $entity->getStats());
        $this->assertSame($stats, $entity->getStats());
    }

    /**
     * Test virtual servers property
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
        $this->assertCount(11, $arrayData);
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

    /**
     * Test allocated memory get method
     */
    public function testGetAllocatedMemory()
    {
        $hardwareServer = new HardwareServerEntity();
        $vs1 = new VirtualServerEntity();
        $vs1->setMemory(1500);
        $vs2 = new VirtualServerEntity();
        $vs2->setMemory(3000);
        $vs3 = new VirtualServerEntity();
        $vs3->setMemory(12000);

        $hardwareServer->setVirtualServers([
            $vs1,
            $vs2,
            $vs3,
        ]);

        $this->assertNotEmpty($hardwareServer->getAllocatedMemory());
        $this->assertEquals(1500 + 3000 + 12000, $hardwareServer->getAllocatedMemory());
    }

    /**
     * Test allocated memory get method
     */
    public function testGetAllocatedDiskSpace()
    {
        $hardwareServer = new HardwareServerEntity();
        $vs1 = new VirtualServerEntity();
        $vs1->setDiskSpace(2000);
        $vs2 = new VirtualServerEntity();
        $vs2->setDiskSpace(5000);
        $vs3 = new VirtualServerEntity();
        $vs3->setDiskSpace(100000);

        $hardwareServer->setVirtualServers([
            $vs1,
            $vs2,
            $vs3,
        ]);

        $this->assertNotEmpty($hardwareServer->getAllocatedDiskSpace());
        $this->assertEquals(2000 + 5000 + 100000, $hardwareServer->getAllocatedDiskSpace());
    }
}
