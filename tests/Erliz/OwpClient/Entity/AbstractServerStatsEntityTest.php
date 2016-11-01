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
 * Class AbstractServerStatsEntityTest
 *
 * @author Stanislav Vetlovskiy <mrerliz@gmail.com>
 */
class AbstractServerStatsEntityTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function goodDataProvider()
    {
        return [
            [[[0.5, 10, 1480], '/', 200, 800, ]],
            [[[0.01, 0, 0], '/mnt/media1', 1, 1, ]],
            [[[200, 200, 200], '/root', 0, 0, ]],
        ];
    }

    /**
     * Test all methods
     * @dataProvider goodDataProvider
     *
     * @param array $testData
     */
    public function testProperties(array $testData)
    {
        /** @var AbstractServerStatsEntity $entity */
        $entity = $this->getMockForAbstractClass(AbstractServerStatsEntity::class);
        $this->assertTrue(is_array($entity->__toArray()));

        $mountEntity = new DiskUsageMountEntity();
        $mountEntity->setMountPoint($testData[1]);
        $diskStat = new DiskUsageStatEntity();
        $diskStat->addMountPoint($mountEntity);
        $ramStat = new RamUsageStatEntity();

        $this->assertSame($entity, $entity->setCPULoadAverage($testData[0]));
        $this->assertSame($entity, $entity->setDiskUsage($diskStat));
        $this->assertSame($entity, $entity->setRamUsage($ramStat));

        $this->assertEquals($testData[0], $entity->getCPULoadAverage());
        $this->assertEquals($diskStat, $entity->getDiskUsage());
        $this->assertEquals($ramStat, $entity->getRamUsage());

        $this->assertTrue(is_array($entity->__toArray()));
        $this->assertNotEmpty($entity->__toArray());
        $this->assertCount(3, $entity->__toArray());
    }
}
