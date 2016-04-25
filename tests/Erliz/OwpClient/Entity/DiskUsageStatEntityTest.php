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
 * Class DiskUsageStatEntityTest
 *
 * @author Stanislav Vetlovskiy <mrerliz@gmail.com>
 */
class DiskUsageStatEntityTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function goodDataProvider()
    {
        return [
            [
                [
                    ['/', 50, 1000, 500, 500],
                    ['/root', 1, 10000, 50, 9950],
                    ['/home', 100, 100, 100, 0],
                ],
            ],
        ];
    }

    /**
     * @dataProvider goodDataProvider
     *
     * @param array $testData
     */
    public function testProperties(array $testData)
    {
        $entity = new DiskUsageStatEntity();

        $mountPoints = [];
        foreach ($testData as $mountPointData) {
            $mountPoint = new DiskUsageMountEntity();
            $mountPoint
                ->setMountPoint($mountPointData[0])
                ->setUsagePercent($mountPointData[1])
                ->setTotalSize($mountPointData[2])
                ->setUsedSize($mountPointData[3])
                ->setFreeSize($mountPointData[4]);
            $mountPoints[] = $mountPoint;
        }

        $this->assertTrue(is_array($entity->getMountPoints()));
        $this->assertEmpty($entity->getMountPoints());
        $this->assertSame($entity, $entity->setMountPoints($mountPoints));
        $this->assertSame($mountPoints, $entity->getMountPoints());

        foreach ($entity->getMountPoints() as $mountPoint) {
            $this->assertSame($mountPoint, $entity->getMountPointByLocation($mountPoint->getMountPoint()));
        }
        $this->assertFalse($entity->getMountPointByLocation('/tmp'));

        $this->assertEquals(
            6,
            $entity->getUsagePercent()
        );
        $this->assertEquals(
            $testData[0][2] + $testData[1][2] + $testData[2][2],
            $entity->getTotalSize()
        );
        $this->assertEquals(
            $testData[0][3] + $testData[1][3] + $testData[2][3],
            $entity->getUsedSize()
        );
        $this->assertEquals(
            $testData[0][4] + $testData[1][4] + $testData[2][4],
            $entity->getFreeSize()
        );

        $this->assertTrue(is_array($entity->jsonSerialize()));
        $this->assertNotEmpty($entity->jsonSerialize());

        $serverJson = json_encode($entity);
        $this->assertEmpty(json_last_error());
        $this->assertTrue(is_string($serverJson));
        $this->assertNotEmpty($serverJson);
        $this->assertContains('mountPoints', $serverJson);
    }
}
