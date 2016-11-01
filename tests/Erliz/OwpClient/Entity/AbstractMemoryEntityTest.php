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
 * Class AbstractMemoryEntityTest
 *
 * @author Stanislav Vetlovskiy <mrerliz@gmail.com>
 */
class AbstractMemoryEntityTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function goodDataProvider()
    {
        return [
            [[20, 1000, 200, 800, ]],
            [[1, 1, 1, 1, ]],
            [[0, 0, 0, 0, ]],
        ];
    }

    /**
     * Test all methods
     *
     * @dataProvider goodDataProvider
     *
     * @param array $testData
     */
    public function testProperties(array $testData)
    {
        /** @var AbstractMemoryEntity $entity */
        $entity = $this->getMockForAbstractClass(AbstractMemoryEntity::class);

        $this->assertTrue(is_array($entity->__toArray()));

        $this->assertSame($entity, $entity->setUsagePercent($testData[0]));
        $this->assertSame($entity, $entity->setTotalSize($testData[1]));
        $this->assertSame($entity, $entity->setUsedSize($testData[2]));
        $this->assertSame($entity, $entity->setFreeSize($testData[3]));

        $this->assertEquals($testData[0], $entity->getUsagePercent());
        $this->assertEquals($testData[1], $entity->getTotalSize());
        $this->assertEquals($testData[2], $entity->getUsedSize());
        $this->assertEquals($testData[3], $entity->getFreeSize());

        $this->assertTrue(is_array($entity->__toArray()));
        $this->assertNotEmpty($entity->__toArray());
        $this->assertCount(4, $entity->__toArray());
    }
}
