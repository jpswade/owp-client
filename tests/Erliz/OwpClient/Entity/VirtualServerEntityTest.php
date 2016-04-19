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

use DateTime;
use Erliz\OwpClient\Exception\InvalidArgumentException;
use PHPUnit_Framework_TestCase;

/**
 * Class VirtualServerEntityTest
 *
 * @author Stanislav Vetlovskiy <mrerliz@gmail.com>
 */
class VirtualServerEntityTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function entityProvider()
    {
        return [
            [
                [
                    'cpuLimit'           => 1000,
                    'cpuUnits'           => 1000,
                    'cpus'               => 12,
                    'dailyBackup'        => false,
                    'description'        => 'Db',
                    'diskSpace'          => 50,
                    'expirationDate'     => new DateTime(),
                    'hardwareServerId'   => 4,
                    'hostName'           => 'db1.vz',
                    'id'                 => 1,
                    'identity'           => 1,
                    'ipAddress'          => '198.162.88.51',
                    'memory'             => 6096,
                    'nameserver'         => '198.162.88.2',
                    'origOSTemplate'     => 'linux-x86_64-vz-4',
                    'origServerTemplate' => 'vswap-2g',
                    'searchDomain'       => 'example.com',
                    'startOnBoot'        => true,
                    'state'              => 'running',
                    'userId'             => 1,
                    'vswap'              => 6096,
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
        $virtualServer = new VirtualServerEntity();
        $virtualServer = $this->fillEntityByData($virtualServer, $entityData);

        $this->assertTrue(is_int($virtualServer->getCpuLimit()));
        $this->assertEquals($entityData['cpuLimit'], $virtualServer->getCpuLimit());

        $this->assertTrue(is_int($virtualServer->getCpuUnits()));
        $this->assertEquals($entityData['cpuUnits'], $virtualServer->getCpuUnits());

        $this->assertTrue(is_int($virtualServer->getCpus()));
        $this->assertEquals($entityData['cpus'], $virtualServer->getCpus());

        $this->assertTrue(is_bool($virtualServer->isDailyBackup()));
        $this->assertEquals($entityData['dailyBackup'], $virtualServer->isDailyBackup());

        $this->assertTrue(is_string($virtualServer->getDescription()));
        $this->assertEquals($entityData['description'], $virtualServer->getDescription());

        $this->assertTrue(is_int($virtualServer->getDiskSpace()));
        $this->assertEquals($entityData['diskSpace'], $virtualServer->getDiskSpace());

        $this->assertInstanceOf('DateTime', $virtualServer->getExpirationDate());
        $this->assertEquals($entityData['expirationDate'], $virtualServer->getExpirationDate());

        $this->assertTrue(is_int($virtualServer->getHardwareServerId()));
        $this->assertEquals($entityData['hardwareServerId'], $virtualServer->getHardwareServerId());

        $this->assertTrue(is_string($virtualServer->getHostName()));
        $this->assertEquals($entityData['hostName'], $virtualServer->getHostName());
        $this->assertEquals($virtualServer->getHostName(), $virtualServer->getHost());

        $this->assertTrue(is_int($virtualServer->getId()));
        $this->assertEquals($entityData['id'], $virtualServer->getId());

        $this->assertTrue(is_int($virtualServer->getIdentity()));
        $this->assertEquals($entityData['identity'], $virtualServer->getIdentity());

        $this->assertTrue(is_string($virtualServer->getIpAddress()));
        $this->assertEquals($entityData['ipAddress'], $virtualServer->getIpAddress());

        $this->assertTrue(is_int($virtualServer->getMemory()));
        $this->assertEquals($entityData['memory'], $virtualServer->getMemory());

        $this->assertTrue(is_string($virtualServer->getNameServer()));
        $this->assertEquals($entityData['nameserver'], $virtualServer->getNameServer());

        $this->assertTrue(is_string($virtualServer->getOriginalOSTemplate()));
        $this->assertEquals($entityData['origOSTemplate'], $virtualServer->getOriginalOSTemplate());

        $this->assertTrue(is_string($virtualServer->getOriginalServerTemplate()));
        $this->assertEquals($entityData['origServerTemplate'], $virtualServer->getOriginalServerTemplate());

        $this->assertTrue(is_string($virtualServer->getSearchDomain()));
        $this->assertEquals($entityData['searchDomain'], $virtualServer->getSearchDomain());

        $this->assertTrue(is_bool($virtualServer->isStartOnBoot()));
        $this->assertEquals($entityData['startOnBoot'], $virtualServer->isStartOnBoot());

        $this->assertTrue(is_string($virtualServer->getState()));
        $this->assertEquals($entityData['state'], $virtualServer->getState());

        $this->assertTrue(is_int($virtualServer->getUserId()));
        $this->assertEquals($entityData['userId'], $virtualServer->getUserId());

        $this->assertTrue(is_int($virtualServer->getVSwap()));
        $this->assertEquals($entityData['vswap'], $virtualServer->getVSwap());
    }

    /**
     * Test setting bad state string
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Unknown
     */
    public function testBadStateSet()
    {
        $virtualServer = new VirtualServerEntity();
        $virtualServer->setState('test_value');
    }

    /**
     * Test ip subnet get methods
     */
    public function testGetIpAddressSubNetwork()
    {
        $virtualServer = new VirtualServerEntity();
        $virtualServer->setIpAddress('192.168.1.101');

        $this->assertEquals('192.168.1.101', $virtualServer->getIpAddressSubNetwork(4));
        $this->assertEquals('192.168.1', $virtualServer->getIpAddressSubNetwork());
        $this->assertEquals('192.168', $virtualServer->getIpAddressSubNetwork(2));
        $this->assertEquals('192', $virtualServer->getIpAddressSubNetwork(1));
    }

    /**
     * Test zones  get methods
     */
    public function testGetHostZones()
    {
        $virtualServer = new VirtualServerEntity();

        $virtualServer->setHostName('vz.test.example.com');
        $this->assertCount(4, $virtualServer->getHostZones());
        $this->assertEquals('com', $virtualServer->getHostZones()[0]);
        $this->assertEquals('vz', $virtualServer->getHostZones()[3]);

        $virtualServer->setHostName('localhost');
        $this->assertFalse($virtualServer->getHostZones());
    }

    /**
     * Test bad expiration date
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage DateTime
     */
    public function testBadExpirationDateSet()
    {
        $virtualServer = new VirtualServerEntity();
        $virtualServer->setExpirationDate('1970-01-01');
    }

    /**
     * @dataProvider entityProvider
     *˚
     * @param array $entityData
     */
    public function testToArray(array $entityData)
    {
        $virtualServer = new VirtualServerEntity();
        $virtualServer = $this->fillEntityByData($virtualServer, $entityData);

        $arrayData = $virtualServer->__toArray();

        $this->assertTrue(is_array($arrayData));
        $this->assertCount(21, $arrayData);
        $this->assertEquals($entityData['hostName'], $arrayData['hostName']);
    }

    /**
     * @dataProvider entityProvider
     *
     * @param array $entityData
     */
    public function testJsonSerialize(array $entityData)
    {
        $virtualServer = new VirtualServerEntity();
        $virtualServer = $this->fillEntityByData($virtualServer, $entityData);

        $this->assertTrue(is_array($virtualServer->jsonSerialize()));
        $this->assertNotEmpty($virtualServer->jsonSerialize());

        $serverJson = json_encode($virtualServer);
        $this->assertEmpty(json_last_error());
        $this->assertTrue(is_string($serverJson));
        $this->assertNotEmpty($serverJson);
        $this->assertContains('hostName', $serverJson);
    }

    /**
     * @param VirtualServerEntity $entity
     * @param array               $entityData
     *
     * @return VirtualServerEntity
     */
    private function fillEntityByData(VirtualServerEntity $entity, array $entityData)
    {
        $entity
            ->setCpuLimit($entityData['cpuLimit'])
            ->setCpuUnits($entityData['cpuUnits'])
            ->setCpus($entityData['cpus'])
            ->setDailyBackup($entityData['dailyBackup'])
            ->setDescription($entityData['description'])
            ->setDiskSpace($entityData['diskSpace'])
            ->setExpirationDate($entityData['expirationDate'])
            ->setHardwareServerId($entityData['hardwareServerId'])
            ->setHostName($entityData['hostName'])
            ->setId($entityData['id'])
            ->setIdentity($entityData['identity'])
            ->setIpAddress($entityData['ipAddress'])
            ->setMemory($entityData['memory'])
            ->setNameServer($entityData['nameserver'])
            ->setOriginalOSTemplate($entityData['origOSTemplate'])
            ->setOriginalServerTemplate($entityData['origServerTemplate'])
            ->setSearchDomain($entityData['searchDomain'])
            ->setStartOnBoot($entityData['startOnBoot'])
            ->setState($entityData['state'])
            ->setUserId($entityData['userId'])
            ->setVSwap($entityData['vswap']);

        return $entity;
    }
}
