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
 * Class HardwareServerStatsEntityTest
 *
 * @author Stanislav Vetlovskiy <mrerliz@gmail.com>
 */
class HardwareServerStatsEntityTest extends PHPUnit_Framework_TestCase
{
    /**
     * Data provider
     *
     * @return array
     */
    public function goodDataProvider()
    {
        return [
            [
                ['test_os'],
            ],
        ];
    }

    /**
     * Test getters and setters
     *
     * @dataProvider goodDataProvider
     *
     * @param array $testData
     */
    public function testOSProperty(array $testData)
    {
        $entity = new HardwareServerStatsEntity();

        $this->assertSame($entity, $entity->setOSVersion($testData[0]));
        $this->assertEquals($testData[0], $entity->getOSVersion());


        $this->assertTrue(is_array($entity->jsonSerialize()));
        $this->assertNotEmpty($entity->jsonSerialize());

        $serverJson = json_encode($entity);
        $this->assertEmpty(json_last_error());
        $this->assertTrue(is_string($serverJson));
        $this->assertNotEmpty($serverJson);
        $this->assertContains('osVersion', $serverJson);
    }
}
