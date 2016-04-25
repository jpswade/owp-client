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
 * Class DiskUsageMountEntityTest
 *
 * @author Stanislav Vetlovskiy <mrerliz@gmail.com>
 */
class DiskUsageMountEntityTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function goodDataProvider()
    {
        return [
            [
                ['/test/path'],
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
        $entity = new DiskUsageMountEntity();

        $this->assertInstanceOf('Erliz\OwpClient\Entity\AbstractMemoryEntity', $entity);
        $this->assertSame($entity, $entity->setMountPoint($testData[0]));
        $this->assertEquals($testData[0], (string) $entity);
        $this->assertEquals($testData[0], $entity->getMountPoint());

        $this->assertTrue(is_array($entity->jsonSerialize()));
        $this->assertNotEmpty($entity->jsonSerialize());

        $serverJson = json_encode($entity);
        $this->assertEmpty(json_last_error());
        $this->assertTrue(is_string($serverJson));
        $this->assertNotEmpty($serverJson);
        $this->assertContains('mountPoint', $serverJson);
    }
}
